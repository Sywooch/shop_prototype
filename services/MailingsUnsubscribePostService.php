<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    GetUnsubscribeEmptyWidgetConfigPostService,
    GetUnsubscribeSuccessWidgetConfigService};
use app\forms\MailingForm;
use app\widgets\{UnsubscribeEmptyWidget,
    UnsubscribeSuccessWidget};
use app\helpers\HashHelper;
use app\removers\EmailsMailingsArrayRemover;
use app\finders\EmailEmailFinder;
use app\models\EmailsMailingsModel;

/**
 * Удаляет связь пользователя с рассылками
 */
class MailingsUnsubscribePostService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на удаление связи пользователя с рассылками
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new MailingForm(['scenario'=>MailingForm::UNSUBSCRIBE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $key = HashHelper::createHash([$form->email]);
                    
                    if ($form->key !== $key) {
                        $service = \Yii::$app->registry->get(GetUnsubscribeEmptyWidgetConfigPostService::class);
                        $mailingsUnsubscribeEmptyWidgetConfig = $service->handle($request);
                        return UnsubscribeEmptyWidget::widget($mailingsUnsubscribeEmptyWidgetConfig);
                    }
                    
                    $transaction  = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(EmailEmailFinder::class, ['email'=>$form->email]);
                        $emailsModel = $finder->find();
                        
                        $emailsMailingsModelArray = [];
                        
                        foreach ($form->id as $id) {
                            $emailsMailingsModel = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::DELETE]);
                            $emailsMailingsModel->id_mailing = $id;
                            $emailsMailingsModel->id_email = $emailsModel->id;
                            if ($emailsMailingsModel->validate() === false) {
                                throw new ErrorException($this->modelError($emailsMailingsModel->errors));
                            }
                            $emailsMailingsModelArray[] = $emailsMailingsModel;
                        }
                        
                        $remover = new EmailsMailingsArrayRemover([
                            'models'=>$emailsMailingsModelArray
                        ]);
                        $remover->remove();
                        
                        $transaction->commit();
                        
                        $service = \Yii::$app->registry->get(GetUnsubscribeSuccessWidgetConfigService::class);
                        $unsubscribeSuccessWidgetArray = $service->handle(['mailingsIdArray'=>$form->id]);
                        
                        return UnsubscribeSuccessWidget::widget($unsubscribeSuccessWidgetArray);
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        throw $t;
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

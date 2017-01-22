<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    GetAccountMailingsFormWidgetConfigService,
    GetAccountMailingsUnsubscribeWidgetConfigService};
use app\forms\MailingForm;
use app\saver\ModelSaver;
use app\widgets\{AccountMailingsFormWidget,
    AccountMailingsUnsubscribeWidget};
use app\models\EmailsMailingsModel;

/**
 * Отменяет подписку
 */
class AccountSubscriptionsAddService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на отмену подписки
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            if (\Yii::$app->user->isGuest === true) {
                throw new ErrorException($this->emptyError('user'));
            }
            
            $form = new MailingForm(['scenario'=>MailingForm::SAVE_ACC]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $user = \Yii::$app->user->identity;
                        
                        $emailsMailingsModel = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::SAVE]);
                        $emailsMailingsModel->id_mailing = $form->id;
                        $emailsMailingsModel->id_email = $user->id_email;
                        if ($emailsMailingsModel->validate() === false) {
                            throw new ErrorException($this->modelError($emailsMailingsModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$emailsMailingsModel
                        ]);
                        $saver->save();
                        
                        $dataArray = [];
                        
                        $service = \Yii::$app->registry->get(GetAccountMailingsUnsubscribeWidgetConfigService::class);
                        $accountMailingsUnsubscribeWidgetConfig = $service->handle();
                        $dataArray['unsubscribe'] = AccountMailingsUnsubscribeWidget::widget($accountMailingsUnsubscribeWidgetConfig);
                        
                        $service = \Yii::$app->registry->get(GetAccountMailingsFormWidgetConfigService::class);
                        $accountMailingsFormWidgetConfig = $service->handle();
                        $dataArray['subscribe'] = AccountMailingsFormWidget::widget($accountMailingsFormWidgetConfig);
                        
                        $transaction->commit();
                        
                        return $dataArray;
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

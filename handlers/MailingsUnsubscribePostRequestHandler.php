<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\UserMailingForm;
use app\widgets\{UnsubscribeEmptyWidget,
    UnsubscribeSuccessWidget};
use app\helpers\HashHelper;
use app\removers\EmailsMailingsArrayRemover;
use app\finders\EmailEmailFinder;
use app\models\EmailsMailingsModel;
use app\finders\MailingsIdFinder;

/**
 * Обрабатывает запрос на удаление связи пользователя с рассылками
 */
class MailingsUnsubscribePostRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Удаляет связь пользователя с рассылками
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new UserMailingForm(['scenario'=>UserMailingForm::UNSUBSCRIBE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $key = HashHelper::createHash([$form->email]);
                    
                    if ($form->key !== $key) {
                        $unsubscribeEmptyWidgetConfig = $this->unsubscribeEmptyWidgetConfig($form->email);
                        return UnsubscribeEmptyWidget::widget($unsubscribeEmptyWidgetConfig);
                    }
                    
                    $transaction  = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(EmailEmailFinder::class, [
                            'email'=>$form->email
                        ]);
                        $emailsModel = $finder->find();
                        
                        $emailsMailingsModel = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::DELETE]);
                        
                        $emailsMailingsModelArray = [];
                        
                        foreach ($form->id as $id) {
                            $rawEmailsMailingsModel = clone $emailsMailingsModel;
                            $rawEmailsMailingsModel->id_mailing = $id;
                            $rawEmailsMailingsModel->id_email = $emailsModel->id;
                            if ($rawEmailsMailingsModel->validate() === false) {
                                throw new ErrorException($this->modelError($rawEmailsMailingsModel->errors));
                            }
                            $emailsMailingsModelArray[] = $rawEmailsMailingsModel;
                        }
                        
                        $remover = new EmailsMailingsArrayRemover([
                            'models'=>$emailsMailingsModelArray
                        ]);
                        $remover->remove();
                        
                        $finder = \Yii::$app->registry->get(MailingsIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $mailingsArray = $finder->find();
                        
                        $unsubscribeSuccessWidgetConfig = $this->unsubscribeSuccessWidgetConfig($mailingsArray);
                        $response = UnsubscribeSuccessWidget::widget($unsubscribeSuccessWidgetConfig);
                        
                        $transaction->commit();
                        
                        return $response;
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
    
    /**
     * Возвращает массив конфигурации для виджета UnsubscribeSuccessWidget
     * @param array $mailingsArray
     */
    public function unsubscribeSuccessWidgetConfig(array $mailingsArray)
    {
        try {
            $dataArray = [];
            
            $dataArray['mailings'] = $mailingsArray;
            $dataArray['template'] = 'unsubscribe-success.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

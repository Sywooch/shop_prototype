<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\handlers\AbstractBaseHandler;
use app\services\{EmailGetSaveEmailService,
    MailingsEmailService};
use app\forms\MailingForm;
use app\savers\EmailsMailingsArraySaver;
use app\finders\{EmailsMailingsEmailFinder,
    MailingsIdFinder};
use app\models\EmailsMailingsModel;
use app\widgets\MailingsSuccessWidget;

/**
 * Обрабатывает запрос на сохранение нового комментария
 */
class MailingsSaveRequestHandler extends AbstractBaseHandler
{
    /**
     * Сохраняет новый комментарий
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new MailingForm(['scenario'=>MailingForm::SAVE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction  = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $service = \Yii::$app->registry->get(EmailGetSaveEmailService::class, [
                            'email'=>$form->email
                        ]);
                        $emailsModel = $service->get();
                        
                        $finder = \Yii::$app->registry->get(EmailsMailingsEmailFinder::class, [
                            'email'=>$form->email
                        ]);
                        $emailsMailingsModelArray = $finder->find();
                        
                        $diffIdArray = array_diff($form->id, ArrayHelper::getColumn($emailsMailingsModelArray, 'id_mailing'));
                        
                        $emailsMailingsModel = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::SAVE]);
                        
                        $rawEmailsMailingsModelArray = [];
                        
                        foreach ($diffIdArray as $id) {
                            $rawEmailsMailingsModel = clone $emailsMailingsModel;
                            $rawEmailsMailingsModel->id_email = $emailsModel->id;
                            $rawEmailsMailingsModel->id_mailing = $id;
                            if ($rawEmailsMailingsModel->validate() === false) {
                                throw new ErrorException($this->modelError($rawEmailsMailingsModel->errors));
                            }
                            $rawEmailsMailingsModelArray[] = $rawEmailsMailingsModel;
                        }
                        
                        $saver = new EmailsMailingsArraySaver([
                            'models'=>$rawEmailsMailingsModelArray
                        ]);
                        $saver->save();
                        
                        $finder = \Yii::$app->registry->get(MailingsIdFinder::class, [
                            'id'=>$diffIdArray
                        ]);
                        $mailingsArray = $finder->find();
                        
                        $mailService = new MailingsEmailService([
                            'email'=>$form->email,
                            'mailingsArray'=>$mailingsArray
                        ]);
                        $mailService->get();
                        
                        $mailingsSuccessWidgetConfig = $this->mailingsSuccessWidgetConfig($mailingsArray);
                        $response = MailingsSuccessWidget::widget($mailingsSuccessWidgetConfig);
                        
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
     * Возвращает массив конфигурации для виджета MailingsSuccessWidget
     * @param array $mailingsArray
     */
    private function mailingsSuccessWidgetConfig(array $mailingsArray)
    {
        try {
            $dataArray = [];
            
            $dataArray['mailings'] = $mailingsArray;
            $dataArray['template'] = 'mailings-success.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

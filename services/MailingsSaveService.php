<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\services\{AbstractBaseService,
    EmailGetSaveEmailService,
    GetMailingsSuccessWidgetConfigService};
use app\forms\MailingForm;
use app\savers\EmailsMailingsArraySaver;
use app\finders\{EmailsMailingsEmailFinder,
    MailingsIdFinder};
use app\models\EmailsMailingsModel;
use app\widgets\MailingsSuccessWidget;

/**
 * Сохраняет новый комментарий
 */
class MailingsSaveService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на сохранение нового комментария
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
                        $service = \Yii::$app->registry->get(EmailGetSaveEmailService::class);
                        $emailsModel = $service->handle(['email'=>$form->email]);
                        
                        $finder = \Yii::$app->registry->get(EmailsMailingsEmailFinder::class, ['email'=>$form->email]);
                        $emailsMailingsModelArray = $finder->find();
                        
                        $diffIdArray = array_diff($form->id, ArrayHelper::getColumn($emailsMailingsModelArray, 'id_mailing'));
                        
                        $rawEmailsMailingsModelArray = [];
                        
                        foreach ($diffIdArray as $id) {
                            $rawEmailsMailingsModel = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::SAVE]);
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
                        
                        $service = \Yii::$app->registry->get(GetMailingsSuccessWidgetConfigService::class);
                        $mailingsSuccessWidgetArray = $service->handle(['diffIdArray'=>$diffIdArray]);
                        
                        $transaction->commit();
                        
                        return MailingsSuccessWidget::widget($mailingsSuccessWidgetArray);
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

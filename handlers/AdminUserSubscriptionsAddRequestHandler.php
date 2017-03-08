<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\AdminUserMailingForm;
use app\savers\ModelSaver;
use app\widgets\{AdminUserMailingsFormWidget,
    AdminUserMailingsUnsubscribeWidget};
use app\models\EmailsMailingsModel;
use app\finders\{MailingsEmailFinder,
    MailingsNotEmailFinder,
    UserIdFinder};

/**
 * Обрабатывает запрос на добавление подписки
 */
class AdminUserSubscriptionsAddRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::SAVE_ADMIN]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(UserIdFinder::class, [
                            'id'=>$form->id_user
                        ]);
                        $usersModel = $finder->find();
                        if (empty($usersModel)) {
                            throw new ErrorException($this->emptyError('usersModel'));
                        }
                        
                        $emailsMailingsModel = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::SAVE]);
                        $emailsMailingsModel->id_mailing = $form->id;
                        $emailsMailingsModel->id_email = $usersModel->id_email;
                        if ($emailsMailingsModel->validate() === false) {
                            throw new ErrorException($this->modelError($emailsMailingsModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$emailsMailingsModel
                        ]);
                        $saver->save();
                        
                        $email = $usersModel->email->email;
                        
                        $finder = \Yii::$app->registry->get(MailingsEmailFinder::class, [
                            'email'=>$email
                        ]);
                        $mailingsArray = $finder->find();
                        
                        $finder = \Yii::$app->registry->get(MailingsNotEmailFinder::class, [
                            'email'=>$email
                        ]);
                        $notMailingsArray = $finder->find();
                        
                        $mailingForm = new AdminUserMailingForm(['id_user'=>$usersModel->id]);
                        $notAdminUserMailingForm = new AdminUserMailingForm(['id_user'=>$usersModel->id]);
                        
                        $dataArray = [];
                        
                        $adminUserMailingsUnsubscribeWidgetConfig = $this->adminUserMailingsUnsubscribeWidgetConfig($mailingsArray, $mailingForm);
                        $dataArray['unsubscribe'] = AdminUserMailingsUnsubscribeWidget::widget($adminUserMailingsUnsubscribeWidgetConfig);
                        
                        $adminUserMailingsFormWidgetConfig = $this->adminUserMailingsFormWidgetConfig($notMailingsArray, $notAdminUserMailingForm);
                        $dataArray['subscribe'] = AdminUserMailingsFormWidget::widget($adminUserMailingsFormWidgetConfig);
                        
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

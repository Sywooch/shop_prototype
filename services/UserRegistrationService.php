<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    EmailGetSaveEmailService,
    GetUserRegistrationSuccessWidgetConfigService,
    RegistrationEmailService};
use app\forms\UserRegistrationForm;
use app\savers\ModelSaver;
use app\models\UsersModel;
use app\widgets\UserRegistrationSuccessWidget;

/**
 * Обрабатывает запрос на регистрацию нового пользователя
 */
class UserRegistrationService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на регистрацию нового пользователя
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $service = \Yii::$app->registry->get(EmailGetSaveEmailService::class);
                        $emailsModel = $service->handle(['email'=>$form->email]);
                        
                        $rawUsersModel = new UsersModel(['scenario'=>UsersModel::SAVE]);
                        $rawUsersModel->id_email = $emailsModel->id;
                        $rawUsersModel->password = password_hash($form->password, PASSWORD_DEFAULT);
                        if ($rawUsersModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawUsersModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawUsersModel
                        ]);
                        $saver->save();
                        
                        $mailService = new RegistrationEmailService();
                        $mailService->handle(['email'=>$form->email]);
                        
                        $transaction->commit();
                        
                        $service = \Yii::$app->registry->get(GetUserRegistrationSuccessWidgetConfigService::class);
                        $userRegistrationSuccessWidgetConfig = $service->handle();
                        
                        return UserRegistrationSuccessWidget::widget($userRegistrationSuccessWidgetConfig);
                        
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

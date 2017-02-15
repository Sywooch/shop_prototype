<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\services\{EmailGetSaveEmailService,
    RegistrationEmailService};
use app\forms\UserRegistrationForm;
use app\savers\ModelSaver;
use app\models\UsersModel;
use app\widgets\UserRegistrationSuccessWidget;

/**
 * Обрабатывает запрос на регистрацию нового пользователя
 */
class UserRegistrationPostRequestHandler extends AbstractBaseHandler
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
                        $service = \Yii::$app->registry->get(EmailGetSaveEmailService::class, [
                            'email'=>$form->email
                        ]);
                        $emailsModel = $service->get();
                        
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
                        
                        $mailService = new RegistrationEmailService([
                            'email'=>$form->email
                        ]);
                        $mailService->get();
                        
                        $userRegistrationSuccessWidgetConfig = $this->userRegistrationSuccessWidgetConfig();
                        $response = UserRegistrationSuccessWidget::widget($userRegistrationSuccessWidgetConfig);
                        
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
     * Возвращает массив настроек для виджета UserRegistrationSuccessWidget
     */
    private function userRegistrationSuccessWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Registration');
            $dataArray['template'] = 'registration-success.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

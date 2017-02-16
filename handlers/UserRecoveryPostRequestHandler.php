<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\services\RecoveryEmailService;
use app\forms\RecoveryPasswordForm;
use app\helpers\HashHelper;
use app\savers\SessionModelSaver;
use app\models\RecoveryModel;
use app\widgets\UserRecoverySuccessWidget;

/**
 * Обрабатывает запрос на генерацию нового пароля
 */
class UserRecoveryPostRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на генерацию нового пароля
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $form = new RecoveryPasswordForm(['scenario'=>RecoveryPasswordForm::GET]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $key = HashHelper::randomString(40);
                    
                    $recoveryModel = new RecoveryModel(['scenario'=>RecoveryModel::SET]);
                    $recoveryModel->email = $form->email;
                    if ($recoveryModel->validate() === false) {
                        throw new ErrorException($this->modelError($recoveryModel->errors));
                    }
                    
                    $saver = new SessionModelSaver([
                        'key'=>$key,
                        'model'=>$recoveryModel,
                        'flash'=>true
                    ]);
                    $saver->save();
                    
                    $mailService = new RecoveryEmailService([
                        'key'=>$key,
                        'email'=>$form->email
                    ]);
                    $mailService->get();
                    
                    $userRecoverySuccessWidgetConfig = $this->userRecoverySuccessWidgetConfig($form->email);
                    return UserRecoverySuccessWidget::widget($userRecoverySuccessWidgetConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив настроек для виджета UserRecoverySuccessWidget
     * @param string $email
     * @return array
     */
    private function userRecoverySuccessWidgetConfig(string $email): array
    {
        try {
            $dataArray = [];
            
            $dataArray['email'] = $email;
            $dataArray['header'] = \Yii::t('base', 'Password recovery');
            $dataArray['template'] = 'recovery-success.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

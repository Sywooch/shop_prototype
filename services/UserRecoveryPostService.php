<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    GetUserRecoverySuccessWidgetConfigService,
    RecoveryEmailService};
use app\forms\RecoveryPasswordForm;
use app\helpers\HashHelper;
use app\savers\SessionModelSaver;
use app\models\RecoveryModel;
use app\widgets\UserRecoverySuccessWidget;

/**
 * Обрабатывает запрос на генерацию нового пароля
 */
class UserRecoveryPostService extends AbstractBaseService
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
                    
                    $mailService = new RecoveryEmailService();
                    $mailService->handle([
                        'key'=>$key,
                        'email'=>$form->email
                    ]);
                    
                    $service = \Yii::$app->registry->get(GetUserRecoverySuccessWidgetConfigService::class);
                    $userRecoverySuccessWidgetConfig = $service->handle(['email'=>$form->email]);
                    
                    return UserRecoverySuccessWidget::widget($userRecoverySuccessWidgetConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

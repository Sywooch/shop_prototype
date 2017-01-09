<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    GetCartWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetCurrencyWidgetConfigService,
    GetSearchWidgetConfigService,
    GetUserInfoWidgetConfigService,
    RecoveryEmailService};
use app\forms\RecoveryPasswordForm;
use app\helpers\HashHelper;
use app\savers\SessionModelSaver;
use app\models\RecoveryModel;

/**
 * Формирует массив данных для рендеринга страницы формы восстановления пароля,
 * обрабатывает переданные в форму данные
 */
class UserRecoveryService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на поиск и обработку данных для 
     * формирования HTML формы восстановления пароля
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
                    
                    $dataArray['successConfig'] = $this->getUserRecoverySuccessArray();
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserRecoverySuccessWidget
     * @return array
     */
    private function getUserRecoverySuccessArray(): array
    {
        try {
            if (empty($this->userRecoverySuccessArray)) {
                $dataArray = [];
                
                $dataArray['email'] = $form->email;
                $dataArray['view'] = 'recovery-success.twig';
                
                $this->userRecoverySuccessArray = $dataArray;
            }
            
            return $this->userRecoverySuccessArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

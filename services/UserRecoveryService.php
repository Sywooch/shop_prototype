<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    ChangeCurrencyFormService,
    FrontendTrait,
    GetCartWidgetConfigService,
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
    use FrontendTrait;
    
    /**
     * @var array данные для UserRecoveryWidget
     */
    private $userRecoveryArray = [];
    /**
     * @var array данные для UserRecoverySuccessWidget
     */
    private $userRecoverySuccessArray = [];
    /**
     * @var RecoveryPasswordForm
     */
    private $form = null;
    
    /**
     * Обрабатывает запрос на поиск и обработку данных для 
     * формирования HTML формы восстановления пароля
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $this->form = new RecoveryPasswordForm(['scenario'=>RecoveryPasswordForm::GET]);
            
            if ($request->isAjax === true) {
                if ($this->form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($this->form);
                }
            }
            
            if ($request->isPost === true) {
                if ($this->form->load($request->post()) === true) {
                    if ($this->form->validate() === true) {
                        $key = HashHelper::randomString(40);
                        
                        $recoveryModel = new RecoveryModel(['scenario'=>RecoveryModel::SET]);
                        $recoveryModel->email = $this->form->email;
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
                            'email'=>$this->form->email
                        ]);
                        
                        $dataArray['successConfig'] = $this->getUserRecoverySuccessArray();
                    }
                }
            }
            
            $service = new GetUserInfoWidgetConfigService();
            $dataArray['userConfig'] = $service->handle();
            
            $service = new GetCartWidgetConfigService();
            $dataArray['cartConfig'] = $service->handle();
            
            $service = new ChangeCurrencyFormService();
            $dataArray['currencyConfig'] = $service->handle();
            
            $dataArray['searchConfig'] = $this->getSearchArray();
            $dataArray['menuConfig'] = $this->getCategoriesArray();
            
            if (!isset($dataArray['successConfig'])) {
                $dataArray['formConfig'] = $this->getUserRecoveryArray();
            }
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserRecoveryWidget
     * @return array
     */
    private function getUserRecoveryArray(): array
    {
        try {
            if (empty($this->userRecoveryArray)) {
                $dataArray = [];
                
                $dataArray['form'] = $this->form;
                $dataArray['view'] = 'recovery-form.twig';
                
                $this->userRecoveryArray = $dataArray;
            }
            
            return $this->userRecoveryArray;
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
                
                $dataArray['email'] = $this->form->email;
                $dataArray['view'] = 'recovery-success.twig';
                
                $this->userRecoverySuccessArray = $dataArray;
            }
            
            return $this->userRecoverySuccessArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    EmailGetSaveEmailService,
    GetCartWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetCurrencyWidgetConfigService,
    GetSearchWidgetConfigService,
    GetUserInfoWidgetConfigService,
    RegistrationEmailService};
use app\forms\{UserLoginForm,
    UserRegistrationForm};
use app\savers\ModelSaver;
use app\models\{EmailsModel,
    UsersModel};

/**
 * Формирует массив данных для рендеринга страницы формы регистрации,
 * обрабатывает переданные в форму данные
 */
class UserRegistrationService extends AbstractBaseService
{
    /**
     * @var array данные для UserRegistrationWidget
     */
    private $userRegistrationArray = [];
    /**
     * @var array данные для UserRegistrationSuccessWidget
     */
    private $userRegistrationSuccessArray = [];
    /**
     * @var UserRegistrationForm
     */
    private $form = null;
    /**
     * @var array данные для UserLoginWidget
     */
    private $userLoginArray = [];
    
    /**
     * Обрабатывает запрос на поиск и обработку данных для 
     * формирования HTML формы регистрации
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $this->form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
            
            if ($request->isAjax === true) {
                if ($this->form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($this->form);
                }
            }
            
            $dataArray = [];
            
            if ($request->isPost === true) {
                if ($this->form->load($request->post()) === true) {
                    if ($this->form->validate() === true) {
                        $transaction  = \Yii::$app->db->beginTransaction();
                        
                        try {
                            $service = new EmailGetSaveEmailService();
                            $emailsModel = $service->handle(['email'=>$this->form->email]);
                            
                            $rawUsersModel = new UsersModel(['scenario'=>UsersModel::SAVE]);
                            $rawUsersModel->id_email = $emailsModel->id;
                            $rawUsersModel->password = password_hash($this->form->password, PASSWORD_DEFAULT);
                            $rawUsersModel->validate();
                            $saver = new ModelSaver([
                                'model'=>$rawUsersModel
                            ]);
                            $saver->save();
                            
                            $mailService = new RegistrationEmailService();
                            $mailService->handle(['email'=>$this->form->email]);
                            
                            $transaction->commit();
                            
                            $dataArray['successConfig'] = $this->getUserRegistrationSuccessArray();
                            $dataArray['loginFormConfig'] = $this->getUserLoginArray();
                        } catch (\Throwable $t) {
                            $transaction->rollBack();
                            throw $t;
                        }
                    }
                }
            }
            
            $service = new GetUserInfoWidgetConfigService();
            $dataArray['userConfig'] = $service->handle();
            
            $service = new GetCartWidgetConfigService();
            $dataArray['cartConfig'] = $service->handle();
            
            $service = new GetCurrencyWidgetConfigService();
            $dataArray['currencyConfig'] = $service->handle();
            
            $service = new GetSearchWidgetConfigService();
            $dataArray['searchConfig'] = $service->handle($request);
            
            $service = new GetCategoriesMenuWidgetConfigService();
            $dataArray['menuConfig'] = $service->handle();
            
            if (!isset($dataArray['successConfig'])) {
                $dataArray['formConfig'] = $this->getUserRegistrationArray();
            }
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserRegistrationWidget
     * @return array
     */
    private function getUserRegistrationArray(): array
    {
        try {
            if (empty($this->userRegistrationArray)) {
                $dataArray = [];
                
                $dataArray['form'] = $this->form;
                $dataArray['view'] = 'registration-form.twig';
                
                $this->userRegistrationArray = $dataArray;
            }
            
            return $this->userRegistrationArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserRegistrationSuccessWidget
     * @return array
     */
    private function getUserRegistrationSuccessArray(): array
    {
        try {
            if (empty($this->userRegistrationSuccessArray)) {
                $dataArray = [];
                
                $dataArray['view'] = 'registration-success.twig';
                
                $this->userRegistrationSuccessArray = $dataArray;
            }
            
            return $this->userRegistrationSuccessArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserLoginWidget
     * @return array
     */
    private function getUserLoginArray(): array
    {
        try {
            if (empty($this->userLoginArray)) {
                $dataArray = [];
                
                $dataArray['form'] = new UserLoginForm(['scenario'=>UserLoginForm::LOGIN]);
                $dataArray['view'] = 'login-form.twig';
                
                $this->userLoginArray = $dataArray;
            }
            
            return $this->userLoginArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

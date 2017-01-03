<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    FrontendTrait};
use app\forms\RecoveryPasswordForm;
use app\finders\{UserEmailFinder,
    RecoverySessionFinder};
use app\helpers\HashHelper;
use app\savers\{ModelSaver,
    SessionSaver};

/**
 * Формирует массив данных для рендеринга страницы формы восстановления пароля,
 * обрабатывает переданные в форму данные
 */
class UserGenerateService extends AbstractBaseService
{
    use FrontendTrait;
    
    /**
     * @var array данные для PasswordGenerateWidget
     */
    private $passwordGenerateArray = [];
    /**
     * @var array данные для PasswordGenerateSuccessWidget
     */
    private $passwordGenerateSuccessArray = [];
    /**
     * @var array данные для PasswordGenerateEmptyWidget
     */
    private $passwordGenerateEmptyArray = [];
    /**
     * @var RecoveryPasswordForm
     */
    private $form = null;
    /**
     * @var string сгенерированный пароль
     */
    private $tempPassword = null;
    
    /**
     * Обрабатывает запрос на поиск и обработку данных для 
     * формирования HTML формы восстановления пароля
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $this->form = new RecoveryPasswordForm(['scenario'=>RecoveryPasswordForm::GET]);
            
            if ($request->isGet === true) {
                $key = $request->get(\Yii::$app->params['recoveryKey']);
                if (empty($key)) {
                    throw new NotFoundHttpException($this->error404());
                }
                $finder = new RecoverySessionFinder([
                    'key'=>$key
                ]);
                $recoveryModel = $finder->find();
                if (empty($recoveryModel)) {
                    $dataArray['emptyConfig'] = $this->getPasswordGenerateEmptyArray();
                } else {
                    if ($recoveryModel->validate() === false) {
                        throw new ErrorException($this->modelError($recoveryModel->errors));
                    }
                    $key = HashHelper::createHash([$recoveryModel->email]);
                    
                    $saver = new SessionSaver([
                        'key'=>$key,
                        'models'=>[$recoveryModel],
                        'flash'=>true
                    ]);
                    $saver->save();
                }
            }
            
            if ($request->isAjax === true) {
                if ($this->form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($this->form);
                }
            }
            
            if ($request->isPost === true) {
                if ($this->form->load($request->post()) === true) {
                    if ($this->form->validate() === true) {
                        $key = HashHelper::createHash([$this->form->email]);
                        $finder = new RecoverySessionFinder([
                            'key'=>$key
                        ]);
                        $recoveryModel = $finder->find();
                        
                        if (empty($recoveryModel) || $recoveryModel->email !== $this->form->email) {
                            $dataArray['emptyConfig'] = $this->getPasswordGenerateEmptyArray();
                        } else {
                            $transaction = \Yii::$app->db->beginTransaction();
                            try {
                                $finder = new UserEmailFinder([
                                    'email'=>$this->form->email
                                ]);
                                $usersModel = $finder->find();
                                
                                $this->tempPassword = HashHelper::randomString();
                                
                                $usersModel->password = password_hash($this->tempPassword, PASSWORD_DEFAULT);
                                
                                $saver = new ModelSaver([
                                    'model'=>$usersModel,
                                ]);
                                $saver->save();
                                
                                $dataArray['successConfig'] = $this->getPasswordGenerateSuccessArray();
                                
                                $transaction->commit();
                            } catch (\Throwable $t) {
                                $transaction->rollBack();
                                throw $t;
                            }
                        }
                    }
                }
            }
            
            $dataArray['userConfig'] = $this->getUserArray();
            $dataArray['cartConfig'] = $this->getCartArray();
            $dataArray['currencyConfig'] = $this->getCurrencyArray();
            $dataArray['searchConfig'] = $this->getSearchArray();
            $dataArray['menuConfig'] = $this->getCategoriesArray();
            
            if (!isset($dataArray['emptyConfig']) && !isset($dataArray['successConfig'])) {
                $dataArray['formConfig'] = $this->getPasswordGenerateArray();
            }
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PasswordGenerateWidget
     * @return array
     */
    private function getPasswordGenerateArray(): array
    {
        try {
            if (empty($this->passwordGenerateArray)) {
                $dataArray = [];
                
                $dataArray['form'] = $this->form;
                $dataArray['view'] = 'generate-form.twig';
                
                $this->passwordGenerateArray = $dataArray;
            }
            
            return $this->passwordGenerateArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PasswordGenerateEmptyWidget
     * @return array
     */
    private function getPasswordGenerateEmptyArray(): array
    {
        try {
            if (empty($this->passwordGenerateEmptyArray)) {
                $dataArray = [];
                
                $dataArray['view'] = 'generate-empty.twig';
                
                $this->passwordGenerateEmptyArray = $dataArray;
            }
            
            return $this->passwordGenerateEmptyArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PasswordGenerateSuccessWidget
     * @return array
     */
    private function getPasswordGenerateSuccessArray(): array
    {
        try {
            if (empty($this->passwordGenerateSuccessArray)) {
                $dataArray = [];
                
                $dataArray['tempPassword'] = $this->tempPassword;
                $dataArray['view'] = 'generate-success.twig';
                
                $this->passwordGenerateSuccessArray = $dataArray;
            }
            
            return $this->passwordGenerateSuccessArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
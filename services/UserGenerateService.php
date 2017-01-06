<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    FrontendTrait};
use app\finders\{UserEmailFinder,
    RecoverySessionFinder};
use app\helpers\HashHelper;
use app\savers\ModelSaver;

/**
 * Формирует массив данных для рендеринга страницы формы восстановления пароля,
 * обрабатывает переданные данные
 */
class UserGenerateService extends AbstractBaseService
{
    use FrontendTrait;
    
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
            $key = $request->get(\Yii::$app->params['recoveryKey']);
            $email = $request->get(\Yii::$app->params['emailKey']);
            if (empty($key) || empty($email)) {
                throw new NotFoundHttpException($this->error404());
            }
            
            $finder = new RecoverySessionFinder([
                'key'=>$key
            ]);
            $recoveryModel = $finder->find();
            
            $dataArray = [];
            
            if (empty($recoveryModel) || $recoveryModel->email !== $email) {
                $dataArray['emptyConfig'] = $this->getPasswordGenerateEmptyArray();
            } else {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $finder = new UserEmailFinder([
                        'email'=>$email
                    ]);
                    $usersModel = $finder->find();
                    if (empty($usersModel)) {
                        throw new ErrorException($this->emptyError('usersModel'));
                    }
                    
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
            
            $dataArray['userConfig'] = $this->getUserArray();
            $dataArray['cartConfig'] = $this->getCartArray();
            $dataArray['currencyConfig'] = $this->getCurrencyArray($request);
            $dataArray['searchConfig'] = $this->getSearchArray();
            $dataArray['menuConfig'] = $this->getCategoriesArray();
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
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

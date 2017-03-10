<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\services\GetCurrentCurrencyModelService;
use app\finders\{CategoriesFinder,
    CurrencyFinder,
    PurchasesSessionFinder,
    RecoverySessionFinder,
    UserEmailFinder};
use app\helpers\HashHelper;
use app\savers\ModelSaver;
use app\models\UsersModel;
use app\forms\ChangeCurrencyForm;
use app\validators\StripTagsValidator;

/**
 * Обрабатывает запрос на поиск данных для 
 * HTML формы восстановления пароля
 */
class UserGenerateRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Генерирует новый пароль пользователя
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $key = $request->get(\Yii::$app->params['recoveryKey']) ?? null;
            $email = $request->get(\Yii::$app->params['emailKey']) ?? null;
            
            if (empty($key) || empty($email)) {
                throw new NotFoundHttpException($this->error404());
            }
            
            $validator = new StripTagsValidator();
            $key = $validator->validate($key);
            $email = $validator->validate($email);
            
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if ($email === false) {
                throw new ErrorException($this->invalidError('email'));
            }
            
            $finder = \Yii::$app->registry->get(RecoverySessionFinder::class, [
                'key'=>$key
            ]);
            $recoveryModel = $finder->find();
            
            $dataArray = [];
            
            if (empty($recoveryModel) || $recoveryModel->email !== $email) {
                $dataArray['passwordGenerateEmptyWidgetConfig'] = $this->passwordGenerateEmptyWidgetConfig();
            } else {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $finder = \Yii::$app->registry->get(UserEmailFinder::class, [
                        'email'=>$email
                    ]);
                    $usersModel = $finder->find();
                    if (empty($usersModel)) {
                        throw new ErrorException($this->emptyError('usersModel'));
                    }
                    
                    $tempPassword = HashHelper::randomString();
                    
                    $usersModel->scenario = UsersModel::UPDATE_PASSW;
                    $usersModel->password = password_hash($tempPassword, PASSWORD_DEFAULT);
                    if ($usersModel->validate() === false) {
                        throw new ErrorException($this->modelError($usersModel->errors));
                    }
                    
                    $saver = new ModelSaver([
                        'model'=>$usersModel,
                    ]);
                    $saver->save();
                    
                    $dataArray['passwordGenerateSuccessWidgetConfig'] = $this->passwordGenerateSuccessWidgetConfig($tempPassword);
                    
                    $transaction->commit();
                } catch (\Throwable $t) {
                    $transaction->rollBack();
                    throw $t;
                }
            }
            
            $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                'key'=>HashHelper::createCurrencyKey()
            ]);
            $currentCurrencyModel = $service->get();
            if (empty($currentCurrencyModel)) {
                throw new ErrorException($this->emptyError('currentCurrencyModel'));
            }
            
            $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                'key'=>HashHelper::createCartKey()
            ]);
            $ordersCollection = $finder->find();
            if (empty($ordersCollection)) {
                throw new ErrorException($this->emptyError('ordersCollection'));
            }
            
            $finder = \Yii::$app->registry->get(CurrencyFinder::class);
            $currencyArray = $finder->find();
            if (empty($currencyArray)) {
                throw new ErrorException($this->emptyError('currencyArray'));
            }
            
            $finder = \Yii::$app->registry->get(CategoriesFinder::class);
            $categoriesModelArray = $finder->find();
            if (empty($categoriesModelArray)) {
                throw new ErrorException($this->emptyError('categoriesModelArray'));
            }
            
            $changeCurrencyForm = new ChangeCurrencyForm([
                'id'=>$currentCurrencyModel->id,
                'url'=>Url::current()
            ]);
            
            $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig(\Yii::$app->user);
            $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($ordersCollection, $currentCurrencyModel);
            $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currencyArray, $changeCurrencyForm);
            $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
            $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig($categoriesModelArray);
            
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
    private function passwordGenerateEmptyWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Password recovery');
            $dataArray['template'] = 'empty.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PasswordGenerateSuccessWidget
     * @param string $tempPassword
     * @return array
     */
    private function passwordGenerateSuccessWidgetConfig(string $tempPassword): array
    {
        try {
            $dataArray = [];
            
            $dataArray['tempPassword'] = $tempPassword;
            $dataArray['header'] = \Yii::t('base', 'Password recovery');
            $dataArray['template'] = 'generate-success.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\services\GetCurrentCurrencyModelService;
use app\finders\{CategoriesFinder,
    CurrencyFinder,
    PurchasesSessionFinder};
use app\forms\{AbstractBaseForm,
    ChangeCurrencyForm,
    UserLoginForm};
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос к форме аутентификации
 */
class UserLoginRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос к форме аутентификации
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                    'key'=>HashHelper::createCartKey()
                ]);
                $ordersCollection = $finder->find();
                if (empty($ordersCollection)) {
                    throw new ErrorException($this->emptyError('ordersCollection'));
                }
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
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
                    'scenario'=>ChangeCurrencyForm::SET,
                    'id'=>$currentCurrencyModel->id,
                    'url'=>Url::current()
                ]);
                
                $userLoginForm = new UserLoginForm(['scenario'=>UserLoginForm::LOGIN]);
                
                $dataArray = [];
                
                $dataArray['userLoginWidgetConfig'] = $this->userLoginWidgetConfig($userLoginForm);
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig(\Yii::$app->user);
                $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($ordersCollection, $currentCurrencyModel);
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currencyArray, $changeCurrencyForm);
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig($categoriesModelArray);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserLoginWidget
     * @param AbstractBaseForm $userLoginForm
     */
    public function userLoginWidgetConfig(AbstractBaseForm $userLoginForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['form'] = $userLoginForm;
            $dataArray['template'] = 'login-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

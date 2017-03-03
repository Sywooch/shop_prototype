<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\helpers\HashHelper;
use app\services\GetCurrentCurrencyModelService;
use app\finders\{CategoriesFinder,
    CurrencyFinder,
    PurchasesSessionFinder};
use app\forms\{ChangeCurrencyForm,
    PurchaseForm};

/**
 * Обрабатывает запрос данных для рендеринга корзины покупок
 */
class CartIndexRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML корзины покупок
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
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
                    'scenario'=>ChangeCurrencyForm::SET,
                    'id'=>$currentCurrencyModel->id,
                    'url'=>Url::current()
                ]);
                
                $purchaseForm = new PurchaseForm();
                
                $dataArray = [];
                
                $dataArray['cartWidgetConfig'] = $this->cartWidgetConfig($ordersCollection, $currentCurrencyModel, $purchaseForm);
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig(\Yii::$app->user);
                $dataArray['shortCartRedirectWidgetConfig'] = $this->shortCartRedirectWidgetConfig($ordersCollection, $currentCurrencyModel);
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currencyArray, $changeCurrencyForm);
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig($categoriesModelArray);
                $dataArray['cartCheckoutLinkWidgetConfig'] = $this->cartCheckoutLinkWidgetConfig();
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CartCheckoutLinkWidget
     * @return array
     */
    private function cartCheckoutLinkWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['template'] = 'cart-checkout-link.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

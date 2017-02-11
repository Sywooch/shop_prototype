<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\{AbstractBaseHandler,
    BaseHandlerTrait,
    BaseFrontendHandlerTrait,
    CartHandlerTrait};
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос данных для рендеринга корзины покупок
 */
class CartIndexRequestHandler extends AbstractBaseHandler
{
    use BaseHandlerTrait, BaseFrontendHandlerTrait, CartHandlerTrait;
    
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
                $key = HashHelper::createCartKey();
                $currentCurrencyModel = $this->getCurrentCurrency();
                $ordersCollection = $this->getOrdersSessionCollection();
                
                $dataArray = [];
                
                $dataArray['cartWidgetConfig'] = $this->cartWidgetConfig($ordersCollection, $currentCurrencyModel);
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig();
                $dataArray['shortCartRedirectWidgetConfig'] = $this->shortCartRedirectWidgetConfig($ordersCollection, $currentCurrencyModel);
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currentCurrencyModel);
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig();
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

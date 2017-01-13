<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета CartCheckoutLinkWidget
 */
class GetCartCheckoutLinkWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета CartCheckoutLinkWidget
     */
    private $cartCheckoutLinkWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета CartCheckoutLinkWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->cartCheckoutLinkWidgetArray)) {
                $dataArray = [];
                
                $dataArray['view'] = 'cart-checkout-link.twig';
                
                $this->cartCheckoutLinkWidgetArray = $dataArray;
            }
            
            return $this->cartCheckoutLinkWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

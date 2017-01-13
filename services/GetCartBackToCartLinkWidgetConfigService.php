<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета CartBackToCartLinkWidget
 */
class GetCartBackToCartLinkWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета CartBackToCartLinkWidget
     */
    private $cartBackToCartLinkWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета CartBackToCartLinkWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->cartBackToCartLinkWidgetArray)) {
                $dataArray = [];
                
                $dataArray['view'] = 'cart-back-to-cart-link.twig';
                
                $this->cartBackToCartLinkWidgetArray = $dataArray;
            }
            
            return $this->cartBackToCartLinkWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

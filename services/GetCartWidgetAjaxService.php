<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCartWidgetConfigService};
use app\widgets\CartWidget;

/**
 * Возвращает массив с HTML строкой данных корзины для ответа в формате JSON
 */
class GetCartWidgetAjaxService extends AbstractBaseService
{
    /**
     * @var array
     */
    private $cartWidgetAjaxArray = [];
    
    /**
     * Возвращает массив с HTML строкой данных
     * @param $request
     * @return string
     */
    public function handle($request=null): string
    {
        try {
            if (empty($this->cartWidgetAjaxArray)) {
                $service = \Yii::$app->registry->get(GetCartWidgetConfigService::class);
                $cartInfoWidget = $service->handle();
                
                $cartInfoWidget['view'] = 'short-cart-ajax.twig';
                
                $this->cartWidgetAjaxArray = CartWidget::widget($cartInfoWidget);
            }
            
            return $this->cartWidgetAjaxArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

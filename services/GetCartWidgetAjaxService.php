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
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->cartWidgetAjaxArray)) {
                $dataArray = [];
                
                $service = new GetCartWidgetConfigService();
                $cartInfoWidget = $service->handle();
                
                $cartInfoWidget['view'] = 'short-cart-ajax.twig';
                
                $dataArray['cartInfo'] = CartWidget::widget($cartInfoWidget);
                
                $this->cartWidgetAjaxArray = $dataArray;
            }
            
            return $this->cartWidgetAjaxArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

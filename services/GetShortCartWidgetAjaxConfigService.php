<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetShortCartWidgetConfigService};
use app\widgets\ShortCartWidget;

/**
 * Возвращает массив с HTML строкой данных корзины для ответа в формате JSON
 */
class GetShortCartWidgetAjaxConfigService extends AbstractBaseService
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
                $service = \Yii::$app->registry->get(GetShortCartWidgetConfigService::class);
                $cartInfoWidget = $service->handle();
                
                $cartInfoWidget['template'] = 'short-cart-ajax.twig';
                
                $this->cartWidgetAjaxArray = $cartInfoWidget;
            }
            
            return $this->cartWidgetAjaxArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

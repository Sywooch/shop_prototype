<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета EmptyProductsWidget
 */
class GetEmptyProductsWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета EmptyProductsWidget
     */
    private $emptyProductsWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета EmptyProductsWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->emptyProductsWidgetArray)) {
                $dataArray = [];
                
                $dataArray['template'] = 'empty-products.twig';
                
                $this->emptyProductsWidgetArray = $dataArray;
            }
            
            return $this->emptyProductsWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

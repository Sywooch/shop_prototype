<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета EmptySphinxWidget
 */
class GetEmptySphinxWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета EmptySphinxWidget
     */
    private $emptySphinxWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета EmptySphinxWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->emptySphinxWidgetArray)) {
                $dataArray = [];
                
                $dataArray['template'] = 'empty-sphinx.twig';
                
                $this->emptySphinxWidgetArray = $dataArray;
            }
            
            return $this->emptySphinxWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

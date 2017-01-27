<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета SearchWidget
 */
class GetSearchWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета SearchWidget
     */
    private $searchWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета SearchWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->searchWidgetArray)) {
                $dataArray = [];
                
                $dataArray['text'] = $request->get(\Yii::$app->params['searchKey']) ?? '';
                $dataArray['template'] = 'search.twig';
                
                $this->searchWidgetArray = $dataArray;
            }
            
            return $this->searchWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

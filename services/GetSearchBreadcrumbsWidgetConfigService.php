<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета SearchBreadcrumbsWidget
 */
class GetSearchBreadcrumbsWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета SearchBreadcrumbsWidget
     */
    private $searchBreadcrumbsWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета SearchBreadcrumbsWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->searchBreadcrumbsWidgetArray)) {
                
                $dataArray = [];
                
                $dataArray['text'] = $request->get(\Yii::$app->params['searchKey']) ?? '';
                
                $this->searchBreadcrumbsWidgetArray = $dataArray;
            }
            
            return $this->searchBreadcrumbsWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

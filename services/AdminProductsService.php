<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetAdminProductsFiltersWidgetConfigService,
    GetAdminProductsPaginationWidgetConfigService,
    GetAdminProductsWidgetConfigService};

/**
 * Формирует массив данных для рендеринга страницы 
 * с перечнем заказов
 */
class AdminProductsService extends AbstractBaseService
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetAdminProductsFiltersWidgetConfigService::class);
                $dataArray['adminProductsFiltersWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetAdminProductsWidgetConfigService::class);
                $dataArray['adminProductsWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetAdminProductsPaginationWidgetConfigService::class);
                $dataArray['paginationWidgetConfig'] = $service->handle($request);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

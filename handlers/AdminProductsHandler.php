<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\handlers\AbstractBaseHandler;
use app\services\{GetAdminCsvProductsFormWidgetConfigService,
    GetAdminProductsFiltersWidgetConfigService,
    GetAdminProductsPaginationWidgetConfigService,
    GetAdminProductsWidgetConfigService};

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем заказов
 */
class AdminProductsHandler extends AbstractBaseHandler
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
                $dataArray['adminProductsFiltersWidgetConfig'] = $service->get();
                
                $service = \Yii::$app->registry->get(GetAdminProductsWidgetConfigService::class);
                $dataArray['adminProductsWidgetConfig'] = $service->get();
                
                $service = \Yii::$app->registry->get(GetAdminProductsPaginationWidgetConfigService::class);
                $dataArray['paginationWidgetConfig'] = $service->get();
                
                $service = \Yii::$app->registry->get(GetAdminCsvProductsFormWidgetConfigService::class);
                $dataArray['adminCsvProductsFormWidgetConfig'] = $service->get();
                
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

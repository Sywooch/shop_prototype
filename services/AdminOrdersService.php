<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetAdminCsvOrdersFormWidgetConfigService,
    GetAdminOrdersFormWidgetConfigService,
    GetAdminOrdersPaginationWidgetConfigService,
    GetOrdersFiltersWidgetConfigService};

/**
 * Формирует массив данных для рендеринга страницы 
 * с перечнем заказов
 */
class AdminOrdersService extends AbstractBaseService
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
                
                $service = \Yii::$app->registry->get(GetOrdersFiltersWidgetConfigService::class);
                $dataArray['оrdersFiltersWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetAdminOrdersFormWidgetConfigService::class);
                $dataArray['adminOrdersFormWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetAdminOrdersPaginationWidgetConfigService::class);
                $dataArray['paginationWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetAdminCsvOrdersFormWidgetConfigService::class);
                $dataArray['adminCsvOrdersFormWidgetConfig'] = $service->handle();
                
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

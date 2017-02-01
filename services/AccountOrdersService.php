<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetAccountOrdersPaginationWidgetConfigService,
    GetAccountOrdersWidgetConfigService,
    GetOrdersFiltersWidgetConfigService};

/**
 * Формирует массив данных для рендеринга страницы с заказами
 */
class AccountOrdersService extends AbstractBaseService
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы с настройками аккаунта
     * @param array $request
     */
    public function handle($request=null)
    {
        try {
            if (empty($this->dataArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetOrdersFiltersWidgetConfigService::class);
                $dataArray['оrdersFiltersWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetAccountOrdersWidgetConfigService::class);
                $dataArray['accountOrdersWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetAccountOrdersPaginationWidgetConfigService::class);
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

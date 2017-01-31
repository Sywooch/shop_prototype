<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    AdminOrdersCsvArrayService};

/**
 * Обрабатывает запрос на сохранение заказов в формате csv
 */
class CsvGetOrdersService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if ($request->isAjax === true) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
                /*$service = \Yii::$app->registry->get(AdminOrdersCsvArrayService::class);
                $ordersQuery = $service->handle();*/
                
                //$file = fopen(\Yii::getAlias(sprintf('%s/orders/orders%s.csv', '@csvroot', time()))), 'w');
                
                /*foreach ($ordersQuery->each(10) as $order) {
                    
                }*/
                
                return \Yii::getAlias(sprintf('%s/orders/orders%s.csv', '@csvroot', time()));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

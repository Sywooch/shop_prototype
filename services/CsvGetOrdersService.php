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
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $service = \Yii::$app->registry->get(AdminOrdersCsvArrayService::class);
                    $purchasesArray = $service->handle();
                    
                    if (!empty($purchasesArray)) {
                        
                    }
                    
                    return 'SUCCESS';
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

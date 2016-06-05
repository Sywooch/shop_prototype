<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use app\mappers\CurrencyMapper;

/**
 * Определяет функции, общие для разных типов контроллеров
 */
abstract class AbstractBaseProductsController extends AbstractBaseController
{
    /**
     * Получает данные, необходимые в нескольких типах контроллеров 
     * @return array
     */
    protected function getDataForRender()
    {
        try {
            $result = parent::getDataForRender();
            
            # Получаю массив объектов валют
            $currencyMapper = new CurrencyMapper([
                'tableName'=>'currency',
                'fields'=>['id', 'currency'],
                'orderByField'=>'currency'
            ]);
            $result['currencyList'] = $currencyMapper->getGroup();
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $result;
    }
}

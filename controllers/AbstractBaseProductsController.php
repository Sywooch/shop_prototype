<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use app\mappers\CurrencyMapper;
use yii\base\ErrorException;

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
            if (!is_array($result = parent::getDataForRender())) {
                throw new ErrorException('Ошибка при формировании массива данных!');
            }
            
            # Получаю массив объектов валют
            $currencyMapper = new CurrencyMapper([
                'tableName'=>'currency',
                'fields'=>['id', 'currency'],
                'orderByField'=>'currency'
            ]);
            $currencyArray = $currencyMapper->getGroup();
            if (!is_array($currencyArray) || empty($currencyArray)) {
                throw new ErrorException('Ошибка при получении данных для рендеринга!');
            }
            $result['currencyList'] = $currencyArray;
            return $result;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

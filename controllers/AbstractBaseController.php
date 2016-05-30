<?php

namespace app\controllers;

use yii\web\Controller;
use app\mappers\CategoriesMapper;
use app\mappers\CurrencyMapper;
use app\mappers\ColorsMapper;
use app\mappers\SizesMapper;
use app\traits\ExceptionsTrait;

/**
 * Определяет функции, общие для разных типов контроллеров
 */
abstract class AbstractBaseController extends Controller
{
    use ExceptionsTrait;
    
    /**
     * Получает данные, необходимые в нескольких типах контроллеров 
     * @return array
     */
    protected function getDataForRender()
    {
        try {
            $result = array();
            
            # Получаю массив объектов категорий
            $categoriesMapper = new CategoriesMapper([
                'tableName'=>'categories',
                'fields'=>['id', 'name', 'seocode'],
                'orderByField'=>'name'
            ]);
            $result['categoriesList'] = $categoriesMapper->getGroup();
            
            # Получаю массив объектов валют
            $currencyMapper = new CurrencyMapper([
                'tableName'=>'currency',
                'fields'=>['id', 'currency'],
                'orderByField'=>'currency'
            ]);
            $result['currencyList'] = $currencyMapper->getGroup();
            
            # Получаю массив объектов цветов для фильтра
            $colorsMapper = new ColorsMapper([
                'tableName'=>'colors',
                'fields'=>['id', 'color'],
                'orderByField'=>'color',
            ]);
            $result['colorsList'] = $colorsMapper->getGroup();
            
            # Получаю массив объектов размеров для фильтра
            $sizesMapper = new SizesMapper([
                'tableName'=>'sizes',
                'fields'=>['id', 'size'],
                'orderByField'=>'size'
            ]);
            $result['sizesList'] = $sizesMapper->getGroup();
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $result;
    }
}

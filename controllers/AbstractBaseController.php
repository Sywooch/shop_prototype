<?php

namespace app\controllers;

use yii\web\Controller;
use app\mappers\CategoriesMapper;
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
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $result;
    }
}
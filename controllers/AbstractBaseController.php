<?php

namespace app\controllers;

use yii\web\Controller;
use app\mappers\CategoriesMapper;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\models\ProductsModel;

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
            $categoriesArray = $categoriesMapper->getGroup();
            if (!is_array($categoriesArray) || empty($categoriesArray)) {
                throw new ErrorException('Ошибка при получении данных для рендеринга!');
            }
            $result['categoriesList'] = $categoriesArray;
            $result['clearCartModel'] = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_CLEAR_CART]);
            return $result;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

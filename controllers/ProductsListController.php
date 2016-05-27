<?php

namespace app\controllers;

use yii\web\Controller;
use app\mappers\ProductsListMapper;
use app\mappers\CategoriesMapper;
use app\mappers\CurrencyMapper;
use app\mappers\ColorsMapper;
use app\mappers\SizesMapper;
use app\traits\ExceptionsTrait;

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends Controller
{
    use ExceptionsTrait;
    
    /**
     * Обрабатывает запрос к списку продуктов, рендерит ответ
     * @return string
     */
    public function actionIndex()
    {
        try {
            # Получаю массив объектов товаров
            $productsMapper = new ProductsListMapper([
                'tableName'=>'products',
                'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
                'orderByField'=>'price'
            ]);
            $productsList = $productsMapper->getGroup();
            
            # Получаю массив объектов категорий
            $categoriesMapper = new CategoriesMapper([
                'tableName'=>'categories',
                'fields'=>['id', 'name'],
                'orderByField'=>'name'
            ]);
            $categoriesList = $categoriesMapper->getGroup();
            
            # Получаю массив объектов валют
            $currencyMapper = new CurrencyMapper([
                'tableName'=>'currency',
                'fields'=>['id', 'currency'],
                'orderByField'=>'currency'
            ]);
            $currencyList = $currencyMapper->getGroup();
            
            # Получаю массив объектов цветов для фильтра
            $colorsMapper = new ColorsMapper([
                'tableName'=>'colors',
                'fields'=>['id', 'color'],
                'orderByField'=>'color',
            ]);
            $colorsList = $colorsMapper->getGroup();
            
            # Получаю массив объектов размеров для фильтра
            $sizesMapper = new SizesMapper([
                'tableName'=>'sizes',
                'fields'=>['id', 'size'],
                'orderByField'=>'size'
            ]);
            $sizesList = $sizesMapper->getGroup();
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $this->render('products-list.twig', ['productsList'=>$productsList, 'categoriesList'=>$categoriesList, 'currencyList'=>$currencyList, 'colorsList'=>$colorsList, 'sizesList'=>$sizesList]);
    }
}

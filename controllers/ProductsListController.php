<?php

namespace app\controllers;

use yii\web\Controller;
use app\mappers\ProductsListMapper;
use yii\base\ErrorException;
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
            $productsMapper = new ProductsListMapper([
                'tableName'=>'products',
                'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
                'orderByField'=>'price'
            ]);
            $productsList = $productsMapper->getGroup();
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $this->render('content.twig', ['productsList'=>$productsList]);
    }
}

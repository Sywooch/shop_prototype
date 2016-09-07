<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\models\ProductsModel;
use app\queries\{GetCategoriesQuery,
    GetProductsQuery};

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос к списку продуктов
     * @return string
     */
    public function actionIndex()
    {
        try {
            $renderArray = array();
            
            $productsQuery = new GetProductsQuery([
                'fields'=>['id', 'date', 'name', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
                'sorting'=>['date'=>SORT_DESC]
            ]);
            $renderArray['productsList'] = $productsQuery->getAll()->all();
            $renderArray['pagination'] = $productsQuery->pagination;
            
            $categoriesQuery = new GetCategoriesQuery([
                'fields'=>['id', 'name', 'seocode'],
                'sorting'=>['name'=>SORT_ASC]
            ]);
            $renderArray['categoriesList'] = $categoriesQuery->getAll()->all();
            
            return $this->render('products-list.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

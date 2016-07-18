<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\exceptions\EmptyListException;
use app\helpers\MappersHelper;
use app\helpers\ModelsInstancesHelper;

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends AbstractBaseController
{
    private $_config = [
        'tableName'=>'products',
        'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
        'otherTablesFields'=>[
            ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
            ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
        ],
        'orderByField'=>'date',
        'getDataSorting'=>false,
    ];
    
    /**
     * Обрабатывает запрос к списку продуктов
     * @return string
     */
    public function actionIndex()
    {
        try {
            $renderArray = array();
            $renderArray['productsList'] = MappersHelper::getProductsList($this->_config);
            $renderArray['categoriesList'] = MappersHelper::getCategoriesList();
            $renderArray['colorsList'] = MappersHelper::getColorsList();
            $renderArray['sizesList'] = MappersHelper::getSizesList();
            $renderArray['brandsList'] = MappersHelper::getBrandsList();
            $renderArray['currencyList'] = MappersHelper::getСurrencyList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('products-list.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает поисковый запрос к списку продуктов
     * @return string
     */
    public function actionSearch()
    {
        try {
            if (!\Yii::$app->request->get(\Yii::$app->params['searchKey'])) {
                return $this->redirect(Url::to(['products-list/index']));
            }
            
            $this->_config['queryClass'] = 'app\queries\ProductsListSearchQueryCreator';
            $renderArray = array();
            try {
                $renderArray['productsList'] = MappersHelper::getProductsList($this->_config);
            } catch (EmptyListException $e) {
                $this->writeErrorInLogs($e, __METHOD__);
            }
            $renderArray['categoriesList'] = MappersHelper::getCategoriesList();
            $renderArray['colorsList'] = MappersHelper::getColorsList();
            $renderArray['sizesList'] = MappersHelper::getSizesList();
            $renderArray['brandsList'] = MappersHelper::getBrandsList();
            $renderArray['currencyList'] = MappersHelper::getСurrencyList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('products-list.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            ['class'=>'app\filters\ProductsListFilter'],
        ];
    }
}

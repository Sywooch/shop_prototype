<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\controllers\AbstractBaseController;
use app\queries\{GetProductsQuery,
    GetSphinxQuery};
use app\helpers\InstancesHelper;
use app\models\SearchModel;

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends AbstractBaseController
{
    /**
     * @var array конфигурация для получения списка записей
     */
    private $_config = [
        'fields'=>['id', 'date', 'name', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'active'],
        'sorting'=>['date'=>SORT_DESC]
    ];
    
    /**
     * Обрабатывает запрос к списку продуктов
     * @return string
     */
    public function actionIndex()
    {
        try {
            $renderArray = $this->common();
            
            return $this->render('products-list.twig', array_merge($renderArray, InstancesHelper::getInstances()));
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
            if (\Yii::$app->request->isGet) {
                return $this->redirect(Url::to(['products-list/index']));
            }
            
            $instances = InstancesHelper::getInstances();
            
            if (\Yii::$app->request->isPost && $instances['searchModel']->load(\Yii::$app->request->post())) {
                $sphinxQuery = new GetSphinxQuery([
                    'tableName'=>'shop',
                    'fields'=>['id'],
                    'text'=>$instances['searchModel']->text
                ]);
                $sphinxArray = $sphinxQuery->getAll()->all();
                
                $this->_config['extraWhere'] = ['products.id'=>ArrayHelper::getColumn($sphinxArray, 'id')];
            }
            
            $renderArray = $this->common();
            
            return $this->render('products-list.twig', array_merge($renderArray, $instances));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инкапсулирует общую для actions функциональность
     * @return array
     */
    private function common()
    {
        try {
            $result = [];
            $productsQuery = new GetProductsQuery($this->_config);
            $result['productsList'] = $productsQuery->getAll()->all();
            $result['paginator'] = $productsQuery->paginator;
            return $result;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

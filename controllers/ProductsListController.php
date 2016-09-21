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
     * Обрабатывает запрос к списку продуктов без учета категорий
     * @return string
     */
    public function actionIndex()
    {
        try {
            return $this->common();
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос к списку продуктов с учетом категории
     * @return string
     */
    public function actionInside()
    {
        try {
            return $this->common();
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
            if (empty(\Yii::$app->params['searchKey'])) {
                throw new ErrorException(\Yii::t('base/errors', 'Not Evaluated {placeholder}!', ['placeholder'=>'$app->params[\'searchKey\']']));
            }
            if (empty(\Yii::$app->request->get(\Yii::$app->params['searchKey']))) {
                return $this->redirect(Url::to(['products-list/index']));
            }
            
            $sphinxQuery = new GetSphinxQuery([
                'tableName'=>'shop',
                'fields'=>['id'],
                'text'=>\Yii::$app->request->get(\Yii::$app->params['searchKey'])
            ]);
            $sphinxArray = $sphinxQuery->getAll()->all();
            
            $this->_config['extraWhere'] = ['products.id'=>ArrayHelper::getColumn($sphinxArray, 'id')];
            
            return $this->common();
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инкапсулирует общую для 
     * - ProductsListController::actionIndex
     * - ProductsListController::actionInside
     * - ProductsListController::actionSearch
     * функциональность
     * @return string
     */
    private function common()
    {
        try {
            $renderArray = array();
            $productsQuery = new GetProductsQuery($this->_config);
            $renderArray['productsList'] = $productsQuery->getAll()->all();
            $renderArray['paginator'] = $productsQuery->paginator;
            
            return $this->render('products-list.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

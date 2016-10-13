<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use yii\sphinx\{MatchExpression,
    Query};
use app\controllers\AbstractBaseController;
use app\helpers\InstancesHelper;
use app\queries\QueryTrait;
use app\models\{ColorsModel,
    FiltersModel,
    ProductsModel};

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends AbstractBaseController
{
    use QueryTrait;
    
    /**
     * Обрабатывает запрос к списку продуктов
     * @return string
     */
    public function actionIndex()
    {
        try {
            $productsQuery = $this->productsListQuery();
            
            $renderArray = array();
            $renderArray['paginator'] = $productsQuery->paginator;
            $renderArray['productsList'] = $productsQuery->all();
            
            # Объект FiltersModel для фильтрации вывода товаров
            $renderArray['filtersModel'] = new FiltersModel(['scenario'=>FiltersModel::GET_FROM_FORM]);
            
            $colorsQuery = $this->colorsListQuery();
            $renderArray['colorsList'] = $colorsQuery->all();
            if (!is_array($renderArray['colorsList']) || !$renderArray['colorsList'][0] instanceof ColorsModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'ColorsModel']));
            }
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            
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
            if (empty(\Yii::$app->request->get(\Yii::$app->params['searchKey']))) {
                return $this->redirect(Url::to(['products-list/index']));
            }
            
            $sphinxQuery = new Query();
            $sphinxQuery->select(['id']);
            $sphinxQuery->from('shop');
            $sphinxQuery->match(new MatchExpression(['*'=>\Yii::$app->request->get(\Yii::$app->params['searchKey'])]));
            $sphinxArray = $sphinxQuery->all();
            
            $productsQuery = $this->productsListQuery(['products.id'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
            
            $renderArray = array();
            $renderArray['paginator'] = $productsQuery->paginator;
            $renderArray['productsList'] = $productsQuery->all();
            
            \Yii::$app->params['breadcrumbs'] = ['label'=>\Yii::t('base', 'Searching results')];
            
            return $this->render('products-list.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

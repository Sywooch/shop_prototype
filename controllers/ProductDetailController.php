<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\helpers\InstancesHelper;
use app\models\ProductsModel;

/**
 * Обрабатывает запросы на получение информации о конкретном продукте
 */
class ProductDetailController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос к конкретному продукту, рендерит ответ
     * @return string
     */
    public function actionIndex()
    {
        try {
            if (empty(\Yii::$app->request->get(\Yii::$app->params['productKey']))) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'productKey']));
            }
            
            $renderArray = array();
            
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'date', 'name', 'seocode', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory']);
            $productsQuery->innerJoin('categories', '[[categories.id]]=[[products.id_category]]');
            $productsQuery->innerJoin('subcategory', '[[subcategory.id]]=[[products.id_subcategory]]');
            $productsQuery->addSelect(['categoryName'=>'[[categories.name]]', 'categorySeocode'=>'[[categories.seocode]]', 'subcategoryName'=>'[[subcategory.name]]', 'subcategorySeocode'=>'[[subcategory.seocode]]']);
            $productsQuery->where(['products.seocode'=>\Yii::$app->request->get(\Yii::$app->params['productKey'])]);
            $renderArray['productsModel'] = $productsQuery->one();
            
            if (!$renderArray['productsModel'] instanceof ProductsModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'ProductsModel']));
            }
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            
            return $this->render('product-detail.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}

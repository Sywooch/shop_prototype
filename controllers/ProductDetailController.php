<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\queries\GetProductsQuery;
use app\helpers\InstancesHelper;

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
            if (empty(\Yii::$app->request->get(\Yii::$app->params['productSeocodeKey']))) {
                throw new ErrorException(\Yii::t('base/errors', 'Incorrect data!'));
            }
            
            $renderArray = array();
            
            $productsQuery = new GetProductsQuery([
                'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory'],
                'extraWhere'=>['products.seocode'=>\Yii::$app->request->get(\Yii::$app->params['productSeocodeKey'])]
            ]);
            $renderArray['productsModel'] = $productsQuery->getOne()->one();
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            
            return $this->render('product-detail.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

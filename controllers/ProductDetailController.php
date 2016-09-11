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
            if (empty(\Yii::$app->params['idKey'])) {
                throw new ErrorException(\Yii::t('base/errors', 'Не определен {keyName}!', ['keyName'=>\Yii::$app->params['idKey']]));
            }
            if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                throw new ErrorException(\Yii::t('base/errors', 'Error getting the ID of the product!'));
            }
            
            $renderArray = array();
            
            $productsQuery = new GetProductsQuery([
                'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory'],
                'extraWhere'=>['products.id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])]
            ]);
            $renderArray['productsModel'] = $productsQuery->getOne()->one();
            
            return $this->render('product-detail.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

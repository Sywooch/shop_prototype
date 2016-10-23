<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
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
                $this->redirect(Url::to(['/products-list/index']));
            }
            
            $renderArray = InstancesHelper::getInstances();
            if (!is_array($renderArray) || empty($renderArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray']));
            }
            
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'code', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory']);
            $productsQuery->where(['[[products.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['productKey'])]);
            $renderArray['productsModel'] = $productsQuery->one();
            if (!$renderArray['productsModel'] instanceof ProductsModel) {
                if (YII_ENV_DEV) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'ProductsModel']));
                } else {
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'ProductsModel']), __METHOD__);
                    return $this->redirect(Url::previous());
                }
            }
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            
            Url::remember();
            
            return $this->render('product-detail.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\CurrencyFilter',
            ],
        ];
    }
}

<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
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
                return $this->redirect(Url::previous());
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
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'ProductsModel']));
            }
            
            $similarQuery = ProductsModel::find();
            $similarQuery->extendSelect(['name', 'price', 'images', 'seocode']);
            $similarQuery->distinct();
            $similarQuery->where(['!=', '[[products.id]]', $renderArray['productsModel']->id]);
            $similarQuery->andWhere(['[[products.id_category]]'=>$renderArray['productsModel']->id_category]);
            $similarQuery->andWhere(['[[products.id_subcategory]]'=>$renderArray['productsModel']->id_subcategory]);
            $similarQuery->innerJoin('{{products_colors}}', '[[products.id]]=[[products_colors.id_product]]');
            $similarQuery->andWhere(['[[products_colors.id_color]]'=>ArrayHelper::getColumn($renderArray['productsModel']->colors, 'id')]);
            $similarQuery->innerJoin('{{products_sizes}}', '[[products.id]]=[[products_sizes.id_product]]');
            $similarQuery->andWhere(['[[products_sizes.id_size]]'=>ArrayHelper::getColumn($renderArray['productsModel']->sizes, 'id')]);
            $similarQuery->limit(\Yii::$app->params['similarLimit']);
            $similarQuery->orderBy(['[[products.date]]'=>SORT_DESC]);
            $similarQuery->asArray();
            $renderArray['similarList'] = $similarQuery->all();
            if (!is_array($renderArray['similarList'])) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'similarList\']']));
            }
            
            $relatedQuery = ProductsModel::find();
            $relatedQuery->extendSelect(['name', 'price', 'images', 'seocode']);
            $relatedQuery->innerJoin('{{related_products}}', '[[products.id]]=[[related_products.id_related_product]]');
            $relatedQuery->where(['[[related_products.id_product]]'=>$renderArray['productsModel']->id]);
            $relatedQuery->orderBy(['[[products.date]]'=>SORT_DESC]);
            $relatedQuery->asArray();
            $renderArray['relatedList'] = $relatedQuery->all();
            if (!is_array($renderArray['relatedList'])) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'relatedList\']']));
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

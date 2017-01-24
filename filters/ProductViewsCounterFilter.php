<?php

namespace app\filters;

use yii\base\{ActionFilter,
    ErrorException};
use app\exceptions\ExceptionsTrait;
use app\finders\ProductDetailFinder;
use app\models\ProductsModel;
use app\savers\ModelSaver;

/**
 * Фиксирует просмотр товара в СУБД
 */
class ProductViewsCounterFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    public function afterAction($action, $result)
    {
        try {
            $seocode = \Yii::$app->request->get(\Yii::$app->params['productKey']);
            
            if (empty($seocode)) {
                throw new ErrorException($this->emptyError('seocode'));
            }
            
            $finder = \Yii::$app->registry->get(ProductDetailFinder::class, ['seocode'=>$seocode]);
            $productsModel = $finder->find();
            
            if (empty($productsModel)) {
                throw new ErrorException($this->emptyError('productsModel'));
            }
            
            $productsModel->scenario = ProductsModel::VIEWS;
            $productsModel->views++;
            if ($productsModel->validate() === false) {
                throw new ErrorException($this->modelError($productsModel->errors));
            }
            
            $saver = new ModelSaver([
                'model'=>$productsModel
            ]);
            $saver->save();
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}


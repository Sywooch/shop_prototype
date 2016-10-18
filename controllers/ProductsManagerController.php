<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\models\{ColorsModel,
    ProductsModel,
    SizesModel};
use app\helpers\InstancesHelper;

/**
 * Управляет добавлением, удвлением, изменением товаров
 */
class ProductsManagerController extends AbstractBaseController
{
    /**
     * Управляет процессом добавления 1 товара
     */
    public function actionAddOne()
    {
        try {
            $rawProductsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT]);
            $rawColorsModel = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_ADD_PRODUCT]);
            $rawSizesModel = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT]);
            
            $renderArray = [];
            
            $renderArray['productsModel'] = $rawProductsModel;
            
            $renderArray['colorsModel'] = $rawColorsModel;
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsQuery->orderBy(['[[colors.color]]'=>SORT_ASC]);
            $renderArray['colorsList'] = $colorsQuery->all();
            if (!$renderArray['colorsList'][0] instanceof ColorsModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'ColorsModel']));
            }
            
            $renderArray['sizesModel'] = $rawSizesModel;
            $renderArray['sizesList'] = SizesModel::find()->extendSelect(['id', 'size'])->orderBy(['[[sizes.size]]'=>SORT_ASC])->all();
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/products-manager/add-one'], 'label'=>\Yii::t('base', 'Add product')];
            
            return $this->render('add-one.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}

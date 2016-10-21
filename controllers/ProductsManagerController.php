<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\web\UploadedFile;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use app\controllers\AbstractBaseController;
use app\models\{BrandsModel,
    CategoriesModel,
    ColorsModel,
    ProductsModel,
    SizesModel,
    SubcategoryModel};
use app\queries\QueryTrait;
use app\helpers\{BrandsHelper,
    CategoriesHelper,
    ColorsHelper,
    InstancesHelper,
    SizesHelper,
    SubcategoryHelper};

/**
 * Управляет добавлением, удвлением, изменением товаров
 */
class ProductsManagerController extends AbstractBaseController
{
    use QueryTrait;
    
    /**
     * Управляет процессом добавления 1 товара
     */
    public function actionAddOne()
    {
        try {
            $rawProductsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT]);
            $rawColorsModel = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_ADD_PRODUCT]);
            $rawSizesModel = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT]);
            $rawBrandsModel = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_ADD_PRODUCT]);
            
            if (\Yii::$app->request->isPost && $rawProductsModel->load(\Yii::$app->request->post()) && $rawColorsModel->load(\Yii::$app->request->post()) && $rawSizesModel->load(\Yii::$app->request->post()) && $rawBrandsModel->load(\Yii::$app->request->post())) {
                if ($rawProductsModel->validate() && $rawColorsModel->validate() && $rawSizesModel->validate() && $rawBrandsModel->validate()) {
                    
                    $transaction = \Yii::$db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!empty($rawProductsModel->images)) {
                            $rawProductsModel->images = UploadedFile::getInstances($rawProductsModel, 'images');
                            $folderName = $rawProductsModel->upload();
                            $rawProductsModel->images = $folderName;
                        }
                        
                        $transaction->commit();
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        throw $t;
                    }
                    
                }
            }
            
            $renderArray = [];
            
            $renderArray['productsModel'] = $rawProductsModel;
            
            $renderArray['categoriesList'] = ArrayHelper::merge([''=>\Yii::$app->params['formFiller']], CategoriesModel::allMap('id', 'name'));
            
            $renderArray['subcategoryList'] = [''=>\Yii::$app->params['formFiller']];
            
            if ($rawProductsModel->id_category) {
                $renderArray['subcategoryList'] = ArrayHelper::merge($renderArray['subcategoryList'], SubcategoryHelper::forCategoryMap($rawProductsModel->id_category, 'id', 'name'));
            }
            
            $renderArray['colorsModel'] = $rawColorsModel;
            $renderArray['colorsList'] = ColorsModel::allMap('id', 'color');
            
            $renderArray['sizesModel'] = $rawSizesModel;
            $renderArray['sizesList'] = SizesHelper::allMap('id', 'size');
            
            $renderArray['brandsModel'] = $rawBrandsModel;
            $renderArray['brandsList'] = BrandsHelper::allMap('id', 'brand');
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/products-manager/add-one'], 'label'=>\Yii::t('base', 'Add product')];
            
            return $this->render('add-one.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}

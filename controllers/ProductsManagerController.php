<?php

namespace app\controllers;

use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\db\Transaction;
use app\exceptions\ExceptionsTrait;
use app\models\{BrandsModel,
    CategoriesModel,
    ColorsModel,
    ProductsModel,
    SizesModel,
    SubcategoryModel};
use app\controllers\AbstractBaseController;

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
            
            $categoriesQuery = CategoriesModel::find();
            $categoriesQuery->extendSelect(['id', 'name']);
            $categoriesQuery->orderBy(['[[categories.name]]'=>SORT_ASC]);
            $renderArray['categoriesList'] = ArrayHelper::merge([''=>\Yii::$app->params['formFiller']], $categoriesQuery->allMap('id', 'name'));
            
            $renderArray['subcategoryList'] = [''=>\Yii::$app->params['formFiller']];
            
            if ($rawProductsModel->id_category) {
                $subcategoryQuery = SubcategoryModel::find();
                $subcategoryQuery->extendSelect(['id', 'name']);
                $subcategoryQuery->where(['[[subcategory.id_category]]'=>$rawProductsModel->id_category]);
                $subcategoryQuery->orderBy(['[[subcategory.name]]'=>SORT_ASC]);
                $renderArray['subcategoryList'] = ArrayHelper::merge($renderArray['subcategoryList'], $subcategoryQuery->allMap('id', 'name'));
            }
            
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsQuery->orderBy(['[[colors.color]]'=>SORT_ASC]);
            $renderArray['colorsList'] = $colorsQuery->allMap('id', 'color');
            
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect(['id', 'size']);
            $sizesQuery->orderBy(['[[sizes.size]]'=>SORT_ASC]);
            $renderArray['sizesList'] = $sizesQuery->allMap('id', 'size');
            
            $brandsQuery = BrandsModel::find();
            $brandsQuery->extendSelect(['id', 'brand']);
            $brandsQuery->orderBy(['[[brands.brand]]'=>SORT_ASC]);
            $renderArray['brandsList'] = $brandsQuery->allMap('id', 'brand');
            
            $renderArray['productsModel'] = $rawProductsModel;
            $renderArray['colorsModel'] = $rawColorsModel;
            $renderArray['sizesModel'] = $rawSizesModel;
            $renderArray['brandsModel'] = $rawBrandsModel;
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/products-manager/add-one'], 'label'=>\Yii::t('base', 'Add product')];
            
            return $this->render('add-one.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}

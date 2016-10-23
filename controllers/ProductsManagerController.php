<?php

namespace app\controllers;

use yii\base\ErrorException;
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
                            if (empty($rawProductsModel->images) || !$rawProductsModel->images[0] instanceof UploadedFile) {
                                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'UploadedFile']));
                            }
                            $folderName = $rawProductsModel->upload();
                            if (!is_string($folderName) || empty($folderName)) {
                                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'string $folderName']));
                            }
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
            $categoriesArray = $categoriesQuery->allMap('id', 'name');
            if (!is_array($categoriesArray) || empty($categoriesArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $categoriesArray']));
            }
            $renderArray['categoriesList'] = ArrayHelper::merge([''=>\Yii::$app->params['formFiller']], $categoriesArray);
            
            $renderArray['subcategoryList'] = [''=>\Yii::$app->params['formFiller']];
            
            if ($rawProductsModel->id_category) {
                $subcategoryQuery = SubcategoryModel::find();
                $subcategoryQuery->extendSelect(['id', 'name']);
                $subcategoryQuery->where(['[[subcategory.id_category]]'=>$rawProductsModel->id_category]);
                $subcategoryQuery->orderBy(['[[subcategory.name]]'=>SORT_ASC]);
                $subcategoryArray = $subcategoryQuery->allMap('id', 'name');
                if (!is_array($subcategoryArray) || empty($subcategoryArray)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $subcategoryArray']));
                }
                $renderArray['subcategoryList'] = ArrayHelper::merge($renderArray['subcategoryList'], $subcategoryArray);
            }
            
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsQuery->orderBy(['[[colors.color]]'=>SORT_ASC]);
            $colorsArray = $colorsQuery->allMap('id', 'color');
            if (!is_array($colorsArray) || empty($colorsArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $colorsArray']));
            }
            $renderArray['colorsList'] = $colorsArray;
            
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect(['id', 'size']);
            $sizesQuery->orderBy(['[[sizes.size]]'=>SORT_ASC]);
            $sizesArray = $sizesQuery->allMap('id', 'size');
            if (!is_array($sizesArray) || empty($sizesArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $sizesArray']));
            }
            $renderArray['sizesList'] = $sizesArray;
            
            $brandsQuery = BrandsModel::find();
            $brandsQuery->extendSelect(['id', 'brand']);
            $brandsQuery->orderBy(['[[brands.brand]]'=>SORT_ASC]);
            $brandsArray = $brandsQuery->allMap('id', 'brand');
            if (!is_array($brandsArray) || empty($brandsArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $brandsArray']));
            }
            $renderArray['brandsList'] = $brandsArray;
            
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

<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use yii\web\{UploadedFile,
    Response};
use yii\db\Transaction;
use yii\widgets\ActiveForm;
use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;
use app\models\{BrandsModel,
    CategoriesModel,
    ColorsModel,
    ProductsColorsModel,
    ProductsModel,
    ProductsSizesModel,
    SizesModel,
    SubcategoryModel};
use app\helpers\PicturesHelper;

/**
 * Коллекция сервис-методов ProductsManagerController
 */
class ProductsManagerControllerHelper extends AbstractControllerHelper
{
    /**
     * @var object ProductsModel
     */
    private static $_rawProductsModel;
    /**
     * @var object ColorsModel
     */
    private static $_rawColorsModel;
    /**
     * @var object SizesModel
     */
    private static $_rawSizesModel;
    
    /**
     * Конструирует данные для ProductsManagerController::actionAddOne()
     * @return array
     */
    public static function oneGet(): array
    {
        try {
            self::models();
            
            $renderArray = [];
            $renderArray = ArrayHelper::merge($renderArray, self::getCategoriesList());
            $renderArray = ArrayHelper::merge($renderArray, self::getSubcategoryList());
            $renderArray = ArrayHelper::merge($renderArray, self::getColorsList());
            $renderArray = ArrayHelper::merge($renderArray, self::getSizesList());
            $renderArray = ArrayHelper::merge($renderArray, self::getBrandsList());
            
            $renderArray['productsModel'] = self::$_rawProductsModel;
            $renderArray['colorsModel'] = self::$_rawColorsModel;
            $renderArray['sizesModel'] = self::$_rawSizesModel;
            
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-manager/add-one'], 'label'=>\Yii::t('base', 'Add product')];
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает данные POST запроса для ProductsManagerController::actionAddOne()
     * @return string
     */
    public static function onePost(): string
    {
        try {
            self::models();
            
            if (self::$_rawProductsModel->load(\Yii::$app->request->post()) && self::$_rawColorsModel->load(\Yii::$app->request->post()) && self::$_rawSizesModel->load(\Yii::$app->request->post())) {
                if (self::$_rawProductsModel->validate() && self::$_rawColorsModel->validate() && self::$_rawSizesModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!empty(self::$_rawProductsModel->images)) {
                            self::$_rawProductsModel->images = UploadedFile::getInstances(self::$_rawProductsModel, 'images');
                            $folderName = self::$_rawProductsModel->upload();
                            self::$_rawProductsModel->images = $folderName;
                        }
                        
                        if (!self::$_rawProductsModel->save(false)) {
                            throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'ProductsModel::save']));
                        }
                        
                        $productsQuery = ProductsModel::find();
                        $productsQuery->extendSelect(['id', 'seocode']);
                        $productsQuery->where(['[[products.seocode]]'=>self::$_rawProductsModel->seocode]);
                        $productsModel = $productsQuery->one();
                        
                        $count = ProductsColorsModel::batchInsert($productsModel, self::$_rawColorsModel);
                        if ($count < 1) {
                            throw new ExecutionException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'ProductsColorsModel::batchInsert']));
                        }
                        
                        $count = ProductsSizesModel::batchInsert($productsModel, self::$_rawSizesModel);
                        if ($count < 1) {
                            throw new ExecutionException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'ProductsSizesModel::batchInsert']));
                        }
                        
                        $transaction->commit();
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        if (!empty($folderName)) {
                            PicturesHelper::remove(\Yii::getAlias('@imagesroot/' . $folderName));
                        }
                        throw $t;
                    }
                }
            }
            
            return !empty($productsModel->seocode) ? $productsModel->seocode : false;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает данные AJAX запроса для ProductsManagerController::actionAddOne()
     * @return array
     */
    public static function oneAjax()
    {
        try {
            self::models();
            
            if (self::$_rawProductsModel->load(\Yii::$app->request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate(self::$_rawProductsModel);
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует модели для 
     * - ProductsManagerController::oneGet
     * - ProductsManagerController::onePost
     * - ProductsManagerController::oneAjax
     */
    public static function models()
    {
        try {
            if (empty(self::$_rawProductsModel)) {
                self::$_rawProductsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT]);
            }
            if (empty(self::$_rawColorsModel)) {
                self::$_rawColorsModel = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_ADD_PRODUCT]);
            }
            if (empty(self::$_rawSizesModel)) {
                self::$_rawSizesModel = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT]);
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет массив $renderArray данными CategoriesModel для 
     * - ProductsManagerController::oneGet
     * - ProductsManagerController::onePost
     * - ProductsManagerController::oneAjax
     * @return array
     */
    private static function getCategoriesList(): array
    {
        try {
            $renderArray = [];
            
            $categoriesQuery = CategoriesModel::find();
            $categoriesQuery->extendSelect(['id', 'name']);
            $categoriesArray = $categoriesQuery->allMap('id', 'name');
            asort($categoriesArray, SORT_STRING);
            $renderArray['categoriesList'] = ArrayHelper::merge([''=>\Yii::$app->params['formFiller']], $categoriesArray);
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет массив $renderArray данными SubcategoryModel для 
     * - ProductsManagerController::oneGet
     * - ProductsManagerController::onePost
     * - ProductsManagerController::oneAjax
     * @param object $rawProductsModel объект ProductsModel
     * @return array
     */
    private static function getSubcategoryList($rawProductsModel=null): array
    {
        try {
            $renderArray = [];
            
            $renderArray['subcategoryList'] = [''=>\Yii::$app->params['formFiller']];
            
            if (!empty($rawProductsModel) && !empty($rawProductsModel->id_category)) {
                $subcategoryQuery = SubcategoryModel::find();
                $subcategoryQuery->extendSelect(['id', 'name']);
                $subcategoryQuery->where(['[[subcategory.id_category]]'=>$rawProductsModel->id_category]);
                $subcategoryArray = $subcategoryQuery->allMap('id', 'name');
                asort($subcategoryArray, SORT_STRING);
                $renderArray['subcategoryList'] = ArrayHelper::merge($renderArray, $subcategoryArray);
            }
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет массив $renderArray данными ColorsModel для 
     * - ProductsManagerController::oneGet
     * - ProductsManagerController::onePost
     * - ProductsManagerController::oneAjax
     * @return array
     */
    private static function getColorsList(): array
    {
        try {
            $renderArray = [];
            
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsArray = $colorsQuery->allMap('id', 'color');
            asort($colorsArray, SORT_STRING);
            $renderArray['colorsList'] = $colorsArray;
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет массив $renderArray данными SizesModel для 
     * - ProductsManagerController::oneGet
     * - ProductsManagerController::onePost
     * - ProductsManagerController::oneAjax
     * @return array
     */
    private static function getSizesList(): array
    {
        try {
            $renderArray = [];
            
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect(['id', 'size']);
            $sizesArray = $sizesQuery->allMap('id', 'size');
            asort($sizesArray, SORT_STRING);
            $renderArray['sizesList'] = $sizesArray;
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет массив $renderArray данными BrandsModel для 
     * - ProductsManagerController::oneGet
     * - ProductsManagerController::onePost
     * - ProductsManagerController::oneAjax
     * @return array
     */
    private static function getBrandsList(): array
    {
        try {
            $renderArray = [];
            
            $brandsQuery = BrandsModel::find();
            $brandsQuery->extendSelect(['id', 'brand']);
            $brandsArray = $brandsQuery->allMap('id', 'brand');
            asort($brandsArray, SORT_STRING);
            $renderArray['brandsList'] = ArrayHelper::merge([''=>\Yii::$app->params['formFiller']], $brandsArray);
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}

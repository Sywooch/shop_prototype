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
    public static function addOneGet(): array
    {
        try {
            self::models();
            
            $renderArray = [];
            $renderArray['categoriesList'] = self::getCategories();
            $renderArray['subcategoryList'] = [''=>\Yii::$app->params['formFiller']];
            $renderArray['colorsList'] = self::getColors();
            $renderArray['sizesList'] = self::getSizes();
            $renderArray['brandsList'] = self::getBrands();
            
            $renderArray['productsModel'] = self::$_rawProductsModel;
            $renderArray['colorsModel'] = self::$_rawColorsModel;
            $renderArray['sizesModel'] = self::$_rawSizesModel;
            
            self::breadcrumbs();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает данные POST запроса для ProductsManagerController::actionAddOne()
     * @return mixed
     */
    public static function addOnePost(): string
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
                            throw new ErrorException(ExceptionsTrait::methodError('ProductsModel::save'));
                        }
                        
                        $productsModel = self::getProduct(self::$_rawProductsModel['seocode']);
                        
                        $count = ProductsColorsModel::batchInsert($productsModel, self::$_rawColorsModel);
                        if ($count < 1) {
                            throw new ErrorException(ExceptionsTrait::methodError('ProductsColorsModel::batchInsert'));
                        }
                        
                        $count = ProductsSizesModel::batchInsert($productsModel, self::$_rawSizesModel);
                        if ($count < 1) {
                            throw new ErrorException(ExceptionsTrait::methodError('ProductsSizesModel::batchInsert'));
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
    public static function addOneAjax()
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
     * Конструирует модели
     */
    private static function models()
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
     * Возвращает массив данных CategoriesModel 
     * @return array
     */
    private static function getCategories(): array
    {
        try {
            $categoriesQuery = CategoriesModel::find();
            $categoriesQuery->extendSelect(['id', 'name']);
            $categoriesQuery->asArray();
            $categoriesArray = $categoriesQuery->all();
            $categoriesArray = ArrayHelper::map($categoriesArray, 'id', 'name');
            asort($categoriesArray, SORT_STRING);
            $categoriesArray = ArrayHelper::merge([''=>\Yii::$app->params['formFiller']], $categoriesArray);
            
            return $categoriesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных ColorsModel 
     * @return array
     */
    private static function getColors(): array
    {
        try {
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsQuery->asArray();
            $colorsArray = $colorsQuery->all();
            $colorsArray = ArrayHelper::map($colorsArray, 'id', 'color');
            asort($colorsArray, SORT_STRING);
            
            return $colorsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных SizesModel 
     * @return array
     */
    private static function getSizes(): array
    {
        try {
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect(['id', 'size']);
            $sizesQuery->asArray();
            $sizesArray = $sizesQuery->all();
            $sizesArray = ArrayHelper::map($sizesArray, 'id', 'size');
            asort($sizesArray, SORT_STRING);
            
            return $sizesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных BrandsModel 
     * @return array
     */
    private static function getBrands(): array
    {
        try {
            $brandsQuery = BrandsModel::find();
            $brandsQuery->extendSelect(['id', 'brand']);
            $brandsQuery->asArray();
            $brandsArray = $brandsQuery->all();
            $brandsArray = ArrayHelper::map($brandsArray, 'id', 'brand');
            asort($brandsArray, SORT_STRING);
            $brandsArray = ArrayHelper::merge([''=>\Yii::$app->params['formFiller']], $brandsArray);
            
            return $brandsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект ProductsModel
     * @param string seocode
     * @return object ProductsModel
     */
    private static function getProduct(string $seocode): ProductsModel
    {
        try {
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'seocode']);
            $productsQuery->where(['[[products.seocode]]'=>$seocode]);
            $productsModel = $productsQuery->one();
            
            return $productsModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет данными массив \Yii::$app->params['breadcrumbs'] 
     */
    private static function breadcrumbs()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-manager/add-one'], 'label'=>\Yii::t('base', 'Add product')];
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}

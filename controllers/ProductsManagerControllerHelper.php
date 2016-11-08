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
use app\models\{ColorsModel,
    ProductsColorsModel,
    ProductsModel,
    ProductsSizesModel,
    SizesModel};
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
            $renderArray['categoriesList'] = self::categoriesMap(true);
            $renderArray['subcategoryList'] = [''=>\Yii::$app->params['formFiller']];
            $renderArray['colorsList'] = self::colorsMap(true);
            $renderArray['sizesList'] = self::sizesMap(true);
            $renderArray['brandsList'] = self::brandsMap(true);
            
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
                        
                        $productsModel = self::getProduct(self::$_rawProductsModel['seocode'], false, false, false);
                        
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
            
            return !empty($productsModel->seocode) ? $productsModel->seocode : '';
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
     * Возвращает массив CategoriesModel
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    private static function categoriesMap(bool $asArray=false): array
    {
        try {
            $categoriesArray = self::getCategories($asArray);
            $categoriesArray = ArrayHelper::map($categoriesArray, 'id', 'name');
            asort($categoriesArray, SORT_STRING);
            $categoriesArray = ArrayHelper::merge([''=>\Yii::$app->params['formFiller']], $categoriesArray);
            
            return $categoriesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив ColorsModel
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    private static function colorsMap(bool $asArray=false): array
    {
        try {
            $colorsArray = self::getColors([], $asArray);
            $colorsArray = ArrayHelper::map($colorsArray, 'id', 'color');
            asort($colorsArray, SORT_STRING);
            
            return $colorsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных SizesModel
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    private static function sizesMap(bool $asArray=false): array
    {
        try {
            $sizesArray = self::getSizes([], $asArray);
            $sizesArray = ArrayHelper::map($sizesArray, 'id', 'size');
            asort($sizesArray, SORT_STRING);
            
            return $sizesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных BrandsModel 
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    private static function brandsMap(bool $asArray=false): array
    {
        try {
            $brandsArray = self::getBrands($asArray);
            $brandsArray = ArrayHelper::map($brandsArray, 'id', 'brand');
            asort($brandsArray, SORT_STRING);
            $brandsArray = ArrayHelper::merge([''=>\Yii::$app->params['formFiller']], $brandsArray);
            
            return $brandsArray;
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

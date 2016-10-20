<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractBaseModel,
    CategoriesModel,
    SizesModel,
    SubcategoryModel};
use app\helpers\{PicturesHelper,
    TransliterationHelper};
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы products
 */
class ProductsModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы добавления товара
    */
    const GET_FROM_ADD_PRODUCT = 'getFromAddProduct';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'products';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_ADD_PRODUCT=>['code', 'name', 'description', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'active', 'total_products', 'seocode'],
        ];
    }
    
    public function rules()
    {
        return [
            [['date', 'code', 'name', 'description', 'short_description', 'total_products', 'seocode'], 'app\validators\StripTagsValidator'],
            [['code', 'name', 'description', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'total_products'], 'required', 'on'=>self::GET_FROM_ADD_PRODUCT],
            [['code'], 'app\validators\ProductCodeValidator', 'on'=>self::GET_FROM_ADD_PRODUCT],
            [['seocode'], 'app\validators\ProductSeocodeValidator', 'on'=>self::GET_FROM_ADD_PRODUCT],
            [['images'], 'image', 'extensions'=>['jpeg', 'jpg', 'gif', 'png'], 'mimeTypes'=>'image/*', 'maxSize'=>1024*1024, 'maxFiles'=>5, 'on'=>self::GET_FROM_ADD_PRODUCT],
        ];
    }
    
    /**
     * Получает объект CategoriesModel, с которой связан текущий объект ProductsModel
     * @return object CategoriesModel
     */
    public function getCategories()
    {
        try {
            return $this->hasOne(CategoriesModel::className(), ['id'=>'id_category'])->inverseOf('products');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект SubcategoryModel, с которой связан текущий объект ProductsModel
     * @return object SubcategoryModel
     */
    public function getSubcategory()
    {
        try {
            return $this->hasOne(SubcategoryModel::className(), ['id'=>'id_subcategory']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив ColorsModel, с которыми связан текущий объект ProductsModel
     * @return array ColorsModel
     */
    public function getColors()
    {
        try {
            return $this->hasMany(ColorsModel::className(), ['id'=>'id_color'])->viaTable('products_colors', ['id_product'=>'id']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив SizesModel, с которыми связан текущий объект ProductsModel
     * @return array SizesModel
     */
    public function getSizes()
    {
        try {
            return $this->hasMany(SizesModel::className(), ['id'=>'id_size'])->viaTable('products_sizes', ['id_product'=>'id']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Загружает изображения
     * @param bool $thumbn будут ли созданы эскизы, 
     * по умолчанию true, эскизы будут созданы
     * @return string
     */
    public function upload($thumbn=true): string
    {
        try {
            $folderName = time();
            $directoryPath = \Yii::getAlias('@imagesroot/' . $folderName);
            if (!file_exists($directoryPath)) {
                if (!mkdir($directoryPath, 0775)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'mkdir']));
                }
            }
            
            foreach ($this->images as $image) {
                if (!PicturesHelper::resize($image)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PicturesHelper::createPictures']));
                }
                if (!$image->saveAs($directoryPath . '/' . $image->baseName . '.' . $image->extension)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'$image->saveAs']));
                }
            }
            
            if ($thumbn) {
                if (!PicturesHelper::createThumbnails($directoryPath)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PicturesHelper::createThumbnails']));
                }
            }
            
            return $folderName;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

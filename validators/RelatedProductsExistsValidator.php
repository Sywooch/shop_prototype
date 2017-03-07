<?php

namespace app\validators;

use yii\validators\Validator;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\finders\ProductsIdFinder;

/**
 * Проверяет валидность данных для формы AdminProductForm
 */
class RelatedProductsExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет существование товаров с переданными ID, 
     * добавляет ошибку, если результат проверки отрицателен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $rawProductsID = explode(',', $model->$attribute);
            
            $finder = new ProductsIdFinder([
                'idArray'=>$rawProductsID
            ]);
            $productsArray = $finder->find();
            $existsProductsID = !empty($productsArray) ? ArrayHelper::getColumn($productsArray, 'id') : [];
            
            $notExists = [];
            
            foreach ($rawProductsID as $id) {
                if (in_array($id, $existsProductsID) === false) {
                    $notExists[] = $id;
                }
            }
            
            if (!empty($notExists)) {
                $this->addError($model, $attribute, \Yii::t('base', 'Goods with these ID do not exist: {placeholder}', ['placeholder'=>implode(',', $notExists)]));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\helpers\TransliterationHelper;
use app\models\ProductsModel;

/**
 * Создает seocode товара, если свойство ProductsModel::seocode не заполнено
 */
class ProductSeocodeValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * @param object $model объект проверяемой модели
     * @param string $attribute имя проверяемого свойства
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            if (empty($model->$attribute)) {
                $seocode = TransliterationHelper::getTransliterationSeparate($model->name);
                if (ProductsModel::find()->where(['[[products.seocode]]'=>$seocode])->exists()) {
                    $seocode .= '-' . $model->code;
                }
                $model->$attribute = $seocode;
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

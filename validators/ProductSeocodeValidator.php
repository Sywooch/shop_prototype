<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\helpers\TransliterationHelper;
use app\models\ProductsModel;

/**
 * Создает seocode товара, если ProductsModel::seocode не заполнено, 
 * модифицирует если seocode товара уже внесен в БД
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
                if (!empty($model->name)) {
                    $seocode = TransliterationHelper::getTransliterationSeparate($model->name);
                    if (!empty($seocode)) {
                        if ($this->check($seocode)) {
                            $seocode .= '-' . (!empty($model->code) ? $model->code : random_bytes(3));
                        }
                        $model->$attribute = $seocode;
                    }
                }
            } else {
                if ($this->check($model->$attribute)) {
                    $this->addError($model, $attribute, \Yii::t('base', 'Product with this seocode already exists!'));
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет существование в БД записи с текущим 
     * @param string $value seocode, который должен быть проверен
     * @return bool
     */
    private function check(string $value): bool
    {
        try {
            $productsQuery = ProductsModel::find();
            $productsQuery->where(['[[products.seocode]]'=>$value]);
            $result = $productsQuery->exists();
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

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
                $seocode = TransliterationHelper::getTransliterationSeparate($model->name);
                if (!empty($seocode)) {
                    if ($this->exists($seocode)) {
                        $seocode .= '-' . $model->code;
                    }
                }
                $model->$attribute = $seocode;
            } else {
                if ($this->exists($model->$attribute)) {
                    $this->addError($model, $attribute, \Yii::t('base', 'Product with this seocode already exists!'));
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет существование в БД записи с текущим seocode
     * @param string $seocode товара
     * @return bool
     */
    private function exists(string $seocode): bool
    {
        try {
            $productsQuery = ProductsModel::find();
            $productsQuery->where(['[[products.seocode]]'=>$seocode]);
            return $productsQuery->exists();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

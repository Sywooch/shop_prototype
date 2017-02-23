<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\SizeSizeFinder;

/**
 * Проверяет валидность данных для формы SizesForm
 */
class CreateSizeSizeExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет уникальность названия размера в СУБД, 
     * добавляет ошибку, если результат проверки положителен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = new SizeSizeFinder([
                'size'=>$model->$attribute
            ]);
            $result = $finder->find();
            
            if (!empty($result)) {
                $this->addError($model, $attribute, \Yii::t('base', 'Size with this name already exists'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

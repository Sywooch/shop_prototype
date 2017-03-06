<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\MailingNameFinder;

/**
 * Проверяет валидность данных для формы AdminMailingForm
 */
class CreateMailingNameExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет существование расылки с именем в СУБД, 
     * добавляет ошибку, если результат проверки положителен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = new MailingNameFinder([
                'name'=>$model->$attribute
            ]);
            $result = $finder->find();
            
            if (!empty($result)) {
                $this->addError($model, $attribute, \Yii::t('base', 'Mailing with this name already exists'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\validators;

use yii\validators\Validator;
use app\exceptions\ExceptionsTrait;
use app\finders\EmailsMailingsIdMailingFinder;

/**
 * Проверяет валидность данных для формы PaymentsForm
 */
class DeleteMailingUsersExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет существование пользователей, подписанные на проверяемую рассылку, 
     * добавляет ошибку, если результат проверки положителен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = new EmailsMailingsIdMailingFinder([
                'id_mailing'=>$model->$attribute
            ]);
            $result = $finder->find();
            
            if (!empty($result)) {
                $this->addError($model, $attribute, \Yii::t('base', 'In this mail has subscribers. You must first delete or transfer them'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

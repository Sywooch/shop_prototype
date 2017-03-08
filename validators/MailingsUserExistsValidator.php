<?php

namespace app\validators;

use yii\validators\Validator;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\finders\EmailsMailingsEmailFinder;

/**
 * Проверяет валидность данных для формы UserMailingForm
 */
class MailingsUserExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Проверяет, существует ли среди переданных рассылка, в которой еще не участвует переданный email
     * фиксирует ошибку, если результат проверки отрицателен
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $finder = \Yii::$app->registry->get(EmailsMailingsEmailFinder::class, [
                'email'=>$model->email
            ]);
            $resultArray = $finder->find();
            
            if (!empty($resultArray)) {
                $mailingsId = ArrayHelper::getColumn($resultArray, 'id_mailing');
                if (empty(array_diff($model->$attribute, $mailingsId))) {
                    $this->addError($model, $attribute, \Yii::t('base', 'You are already subscribed to the mailing list'));
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

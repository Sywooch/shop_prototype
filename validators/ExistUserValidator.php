<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;

/**
 * Проверяет атрибуты модели UsersModel
 */
class ExistUserValidator extends Validator
{
    use ExceptionsTrait;
    
    /**
     * Константа события выполнения запроса к БД
     */
    const SENT_REQUESTS_TO_DB = 'sentRequestsToDb';
    
    public function init()
    {
        parent::init();
        
        if (YII_DEBUG) {
            $this->on($this::SENT_REQUESTS_TO_DB, ['app\helpers\FixSentRequests', 'fix']); # Регистрирует обработчик, подсчитывающий обращения к БД
        }
    }
    
    /**
     * Проверяет, существует ли учетная запись с таким логином
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $command = \Yii::$app->db->createCommand('SELECT COUNT(*) FROM {{users}} WHERE [[login]]=:login');
            $command->bindValue(':login', $model->$attribute);
            if ($command->queryScalar()) {
                $this->addError($model, $attribute, 'Пользователь с таким логином уже существует!');
            }
            if (YII_DEBUG) {
                $this->trigger($this::SENT_REQUESTS_TO_DB); # Фиксирует выполнение запроса к БД
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

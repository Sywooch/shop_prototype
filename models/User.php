<?php

namespace app\models;

use yii\base\{Model,
    Object};
use app\models\UserInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Объект, предоставляющий доступ к объекту 
 * текущего пользователя приложения
 */
class User extends Object Implements UserInterface
{
    use ExceptionsTrait;
    
    /**
     * Возвращает true, если пользователь не аутентифицирован, 
     * false если аутентифицирован
     */
    public function isGuest()
    {
        try {
            return \Yii::$app->user->isGuest;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект Model, представляющий данные текущего пользователя
     */
    public function getIdentity(): Model
    {
        try {
            return \Yii::$app->user->identity;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

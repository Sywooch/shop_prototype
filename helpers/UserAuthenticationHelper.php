<?php

namespace app\helpers;

use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\mappers\UsersByLoginMapper;
use app\models\UsersModel;

/**
 * Предоставляет методы для аутентификации пользователей
 */
class UserAuthenticationHelper
{
    use ExceptionsTrait;
    
    /**
     * @var array список полей, которые необходимо получит из БД
     */
    public static $_filedsFromDb = ['id', 'login', 'password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'];
    /**
     * @var array список полей, которые необходимо обновить для \Yii::$app->user
     */
    public static $_filedsToUser = ['id', 'login', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'];
    /**
     * @var array знасения для ичистки свойств \Yii::$app->user
     */
    public static $_cleanArray = [
        'id'=>NULL,
        'login'=>NULL,
        'password'=>NULL,
        'rawPassword'=>'',
        'name'=>'',
        'surname'=>'',
        'id_emails'=>0,
        'id_phones'=>0,
        'id_address'=>0,
    ];
    
    /**
     * Заполняет объект \Yii::$app->user данными из БД
     * @param objects объект UserModel, плученный из формы
     * @return boolean
     */
    public static function fill(UsersModel $userFromForm)
    {
        try {
            if (empty(\Yii::$app->params['userFromFormForAuthentication'])) {
                $usersByLoginMapper = new UsersByLoginMapper([
                    'tableName'=>'users',
                    'fields'=>self::$_filedsFromDb,
                    'model'=>$userFromForm
                ]);
                \Yii::$app->params['userFromFormForAuthentication'] = $usersByLoginMapper->getOneFromGroup();
            }
            
            if (is_object(\Yii::$app->params['userFromFormForAuthentication']) && \Yii::$app->params['userFromFormForAuthentication'] instanceof UsersModel) {
                if ($userFromForm->login == \Yii::$app->params['userFromFormForAuthentication']->login && password_verify($userFromForm->rawPassword, \Yii::$app->params['userFromFormForAuthentication']->password)) {
                    foreach (self::$_filedsToUser as $field) {
                        \Yii::$app->user->$field = \Yii::$app->params['userFromFormForAuthentication']->$field;
                    }
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обнуляет свойства объекта \Yii::$app->user данными
     * @return boolean
     */
    public static function clean()
    {
        try {
            \Yii::configure(\Yii::$app->user, self::$_cleanArray);
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}

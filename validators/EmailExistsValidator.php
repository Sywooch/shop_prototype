<?php

namespace app\validators;

use yii\validators\Validator;
use app\traits\ExceptionsTrait;
use app\models\{EmailsModel, 
    UsersModel};
use app\helpers\MappersHelper;

/**
 * Проверяет атрибуты модели EmailsModel
 */
class EmailExistsValidator extends Validator
{
    use ExceptionsTrait;
    
    private static $_registartionMessage = 'Аккаунт с таким email уже существует!';
    private static $_loginMessage = 'Аккаунт с таким email не существует!';
    
    /**
     * Проверяет, существует ли учетная запись с таким логином
     * @param object $model текущий экземпляр модели, атрибут которой проверяется
     * @param string $attribute имя атрибута, значение которого проверяется
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            $id_email = MappersHelper::getEmailsByEmail($model);
            
            if ($model->scenario == EmailsModel::GET_FROM_REGISTRATION_FORM) {
                if (!empty($id_email)) {
                    if (empty(\Yii::$app->params['userFromFormForAuthentication'])) {
                        \Yii::$app->params['userFromFormForAuthentication'] = MappersHelper::getUsersByIdEmails(new UsersModel(['id_emails'=>$id_email->id]));
                    }
                    if (is_object(\Yii::$app->params['userFromFormForAuthentication']) && \Yii::$app->params['userFromFormForAuthentication'] instanceof UsersModel) {
                        $this->addError($model, $attribute, self::$_registartionMessage);
                    }
                }
            }
            
            if ($model->scenario == EmailsModel::GET_FROM_LOGIN_FORM) {
                if (!empty($id_email)) {
                    if (empty(\Yii::$app->params['userFromFormForAuthentication'])) {
                        \Yii::$app->params['userFromFormForAuthentication'] = MappersHelper::getUsersByIdEmails(new UsersModel(['id_emails'=>$id_email->id]));
                    }
                    if (!is_object(\Yii::$app->params['userFromFormForAuthentication']) || !\Yii::$app->params['userFromFormForAuthentication'] instanceof UsersModel) {
                        $this->addError($model, $attribute, self::$_loginMessage);
                    }
                } else {
                    $this->addError($model, $attribute, self::$_loginMessage);
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

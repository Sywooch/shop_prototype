<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;
use app\validators\EmailExistsCreateValidator;
use app\models\EmailsModel;

/**
 * Определяет методы, общие для разных типов сервисных классов, 
 * обслужи вающих контроллеры
 */
class AbstractControllerHelper
{
    public static function saveEmail(EmailsModel $email)
    {
        try {
            if (!(new EmailExistsCreateValidator())->validate($email['email'])) {
                if (!$email->save(false)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'EmailsModel::save']));
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}

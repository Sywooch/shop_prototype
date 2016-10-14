<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;
use app\models\EmailsModel;

/**
 * Коллекция методов для создания хеша
 */
class HashHelper
{
    /**
     * Конструирует хеш с помощью функции sha1
     * @param array $inputArray массив данных для конструирования хеша
     * @return string
     */
    public static function createHash(Array $inputArray): string
    {
        try {
            if (empty($inputArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'Array $inputArray']));
            }
            
            $inputArray[] = \Yii::$app->params['hashSalt'];
            return sha1(implode('', $inputArray));
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует хеш и пишет его во Флеш-сессию в процессе восстановления пароля
     * @param object $tmailsModel
     * @return string
     */
    public static function createHashRestore(EmailsModel $tmailsModel): string
    {
        try {
            $salt = random_bytes(12);
            if (!SessionHelper::writeFlash('restore.' . $tmailsModel->email, $salt)) {
                throw new ErrorException(\Yii::t('base', 'Method error {placeholder}!', ['placeholder'=>'SessionHelper::writeFlash']));
            }
            return self::createHash([$tmailsModel->email, $tmailsModel->id, $tmailsModel->users->id, $salt]);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}

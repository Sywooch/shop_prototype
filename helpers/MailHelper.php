<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

/**
 * Предоставляет функциональность для отправки E-mail сообщений
 */
class MailHelper
{
    /**
     * @var array массив готовых к отправке сообщений
     */
    private static $_messages = [];
    
    /**
     * Формирует и отправляет сообщение пользователю
     * @param array $messagesArray массив данных для создания сообщений. 
     * Каждая группа данных, представляющая письмо является массивом 
     * Элементы массива, представляющего кажлое письмо: 
     * - template - string путь к шаблону
     * - setFrom - array адрес отправителя array('email@address.com' => 'Real Name')
     * - setTo - array адрес получателя array('email@address.com' => 'Real Name')
     * - setBcc - array адрес получателя скрытой копии array('email@address.com' => 'Real Name')
     * - setSubject - string тема письма
     * - dataForTemplate - array данные для генерации шаблона
     * - template, setFrom, setTo, setSubject являются обязательными
     * @return int количество отправленных писем
     */
    public static function send(array $messagesArray): int
    {
        try {
            if (empty($messagesArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $messagesArray']));
            }
            
            foreach ($messagesArray as $messageArray) {
                if (self::verify($messageArray)) {
                    if (!self::configure($messageArray)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'self::configure']));
                    }
                }
            }
            
            if (empty(self::$_messages)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array self::$_messages']));
            }
            
            $sent = \Yii::$app->mailer->sendMultiple(self::$_messages);
            if ($sent < 1) {
                throw new ExecutionException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'\Yii::$app->mailer->sendMultiple']));
            }
            
            return $sent;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует письмо из массива данных
     * @param array $messageArray массив данных письма
     * @return bool
     */
    public static function configure(array $messageArray): bool
    {
        try {
            $message = \Yii::$app->mailer->compose();
            $message->setHtmlBody(\Yii::$app->view->renderFile($messageArray['template'], $messageArray['dataForTemplate']));
            $message->setFrom($messageArray['setFrom']);
            $message->setTo($messageArray['setTo']);
            if (!empty($messageArray['setBcc'])) {
                $message->setBcc($messageArray['setBcc']);
            }
            $message->setSubject($messageArray['setSubject']);
            self::$_messages[] = $message;
            
            return true;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Проверяет корректность данных, которые 
     * будут использоваться для формирования письма 
     * @param array $messageArray массив данных письма
     * @return bool
     */
    public static function verify(array $messageArray): bool
    {
        try {
            if (empty($messageArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $messageArray']));
            }
            if (empty($messageArray['template'])) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'$messageArray[\'template\']']));
            }
            if (empty($messageArray['dataForTemplate'])) {
                $messageArray['dataForTemplate'] = [];
            }
            if (empty($messageArray['setFrom'])) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'$messageArray[\'setFrom\']']));
            }
            if (empty($messageArray['setTo'])) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'$messageArray[\'setTo\']']));
            }
            if (empty($messageArray['setSubject'])) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'$messageArray[\'setSubject\']']));
            }
            
            return true;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}

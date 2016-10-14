<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

/**
 * Предоставляет функциональность для отправки E-mail сообщений
 */
class MailHelper
{
    use ExceptionsTrait;
    
    /**
     * @var array массив готовых к отправке сообщений
     */
    private static $_messages = array();
    
    /**
     * Формирует и отправляет сообщение пользователю
     * @param array $messagesArray массив данных для создания сообщений. 
     * Каждая группа данных, представляющая письмо является массивом
     * Элементы $messagesArray: 
     * - template - string путь к шаблону
     * - setFrom - array адрес отправителя array('email@address.com' => 'Real Name')
     * - setTo - array адрес получателя array('email@address.com' => 'Real Name')
     * - setBcc - array адрес получателя скрытой копии array('email@address.com' => 'Real Name')
     * - setSubject - string тема письма
     * - dataForTemplate - array данные для генерации шаблона
     * - template, setFrom, setTo, setSubject являются обязательными
     * @return int количество отправленных писем
     */
    public static function send(Array $messagesArray): int
    {
        try {
            if (empty($messagesArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'Array $messagesArray']));
            }
            
            foreach ($messagesArray as $messageArray) {
                if (!is_array($messageArray) || empty($messageArray)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'Array $messageArray']));
                }
                
                if (empty($messageArray['template'])) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'template name']));
                }
                if (empty($messageArray['dataForTemplate'])) {
                    $messageArray['dataForTemplate'] = array();
                }
                if (empty($messageArray['setFrom'])) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'setFrom']));
                }
                if (empty($messageArray['setTo'])) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'setTo']));
                }
                if (empty($messageArray['setSubject'])) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'setSubject']));
                }
            
                $message = \Yii::$app->mailer->compose();
                $message->setHtmlBody(\Yii::$app->view->renderFile($messageArray['template'], $messageArray['dataForTemplate']));
                $message->setFrom($messageArray['setFrom']);
                $message->setTo($messageArray['setTo']);
                if (!empty($messageArray['setBcc'])) {
                    $message->setBcc($messageArray['setBcc']);
                }
                $message->setSubject($messageArray['setSubject']);
                self::$_messages[] = $message;
            }
            if (empty(self::$_messages)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'Array $_messages']));
            }
            
            $sent = \Yii::$app->mailer->sendMultiple(self::$_messages);
            
            return $sent;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}

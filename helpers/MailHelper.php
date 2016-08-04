<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;

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
     * @param array $messagesArray массив данных для создания сообщений. Каждая группа данных, представляющая письмо является массивом
     * Элементы $messagesArray: 
     * - template - string имя шаблона
     * - setFrom - array адрес отправителя array('email@address.com' => 'Real Name')
     * - setTo - array адрес получателя array('email@address.com' => 'Real Name')
     * - setBcc - array адрес получателя скрытой копии array('email@address.com' => 'Real Name')
     * - setSubject - string тема письма
     * - dataForTemplate - array данные для генерации шаблона
     * - template, setFrom, setTo, setSubject являются обязательными
     */
    public static function send(Array $messagesArray)
    {
        try {
            if (empty($messagesArray)) {
                throw new ErrorException('Массив данных для отправки пуст!');
            }
            
            foreach ($messagesArray as $messageArray) {
                if (!is_array($messageArray) || empty($messageArray)) {
                    throw new ErrorException('Массив данных письма пуст!');
                }
                
                if (empty($messageArray['template'])) {
                    throw new ErrorException('Не указано имя шаблона!');
                }
                if (empty($messageArray['dataForTemplate'])) {
                    $messageArray['dataForTemplate'] = array();
                }
                if (empty($messageArray['setFrom'])) {
                    throw new ErrorException('Не указан отправитель!');
                }
                if (empty($messageArray['setTo'])) {
                    throw new ErrorException('Не указаны получатели!');
                }
                if (empty($messageArray['setSubject'])) {
                    throw new ErrorException('Не указана тема письма!');
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
                throw new ErrorException('Массив готовых к отправке писем пуст!');
            }
            \Yii::$app->mailer->sendMultiple(self::$_messages);
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}

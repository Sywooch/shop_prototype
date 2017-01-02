<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

/**
 * Предоставляет методы для отправки E-mail сообщений
 */
class MailHelper
{
    /**
     * @var array массив готовых к отправке сообщений
     */
    private static $messagesArray = [];
    
    /**
     * Формирует и отправляет сообщение пользователю
     * @param array $rawMessagesArray массив данных для создания сообщений. 
     * Каждая группа данных, представляющая письмо является массивом 
     * Элементы массива, представляющего каждое письмо: 
     * - template - string путь к шаблону
     * - from - array адрес отправителя array('email@address.com' => 'Real Name')
     * - to - array адрес получателя array('email@address.com' => 'Real Name')
     * - bcc - array адрес получателя скрытой копии array('email@address.com' => 'Real Name')
     * - subject - string тема письма
     * - templateData - array данные для генерации шаблона
     * - template, from, to, subject являются обязательными
     * @return int количество отправленных писем
     */
    public static function send(array $rawMessagesArray): int
    {
        try {
            if (!empty($rawMessagesArray)) {
                foreach ($rawMessagesArray as $messageArray) {
                    self::configure($messageArray);
                }
                
                if (!empty(self::$messagesArray)) {
                    $sent = \Yii::$app->mailer->sendMultiple(self::$messagesArray);
                }
            }
            
            return $sent ?? 0;
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
            $message->setHtmlBody(\Yii::$app->view->renderFile($messageArray['template'], $messageArray['templateData'] ?? []));
            $message->setFrom($messageArray['from']);
            $message->setTo($messageArray['to']);
            if (!empty($messageArray['bcc'])) {
                $message->setBcc($messageArray['bcc']);
            }
            $message->setSubject($messageArray['subject']);
            
            self::$messagesArray[] = $message;
            
            return true;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}

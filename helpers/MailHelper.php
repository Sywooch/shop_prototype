<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

/**
 * Предоставляет методы для отправки E-mail сообщений
 */
class MailHelper
{
    use ExceptionsTrait;
    
    /**
     * @var array массив готовых к отправке сообщений
     */
    private $messagesArray = [];
    /**
     * @var array массив данных для создания сообщений
     */
    private $rawMessagesArray = [];
    
    /**
     * Конструирует объект класса
     * @param array $rawMessagesArray массив данных для создания сообщений
     * Каждая группа данных, представляющая письмо, является массивом 
     * Элементы массива, представляющего письмо: 
     * - html - string с телом сообщения
     * - from - array адрес отправителя array('email@address.com' => 'Real Name')
     * - to - array адрес получателя array('email@address.com' => 'Real Name')
     * - bcc - array адрес получателя скрытой копии array('email@address.com' => 'Real Name')
     * - subject - string тема письма
     * - html, from, to, subject являются обязательными
     */
    public function __construct(array $rawMessagesArray)
    {
        try {
            $this->rawMessagesArray = $rawMessagesArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Формирует и отправляет сообщения пользователю
     * @return int количество отправленных писем
     */
    public function send(): int
    {
        try {
            if (!empty($this->rawMessagesArray)) {
                foreach ($this->rawMessagesArray as $messageArray) {
                    $this->configure($messageArray);
                }
                
                if (!empty($this->messagesArray)) {
                    $sent = \Yii::$app->mailer->sendMultiple($this->messagesArray);
                }
            }
            
            return $sent ?? 0;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует письмо из массива данных
     * @param array $messageArray массив данных письма
     * @return bool
     */
    private function configure(array $messageArray): bool
    {
        try {
            $message = \Yii::$app->mailer->compose();
            $message->setHtmlBody($messageArray['html']);
            $message->setFrom($messageArray['from']);
            $message->setTo($messageArray['to']);
            if (!empty($messageArray['bcc'])) {
                $message->setBcc($messageArray['bcc']);
            }
            $message->setSubject($messageArray['subject']);
            
            $this->messagesArray[] = $message;
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

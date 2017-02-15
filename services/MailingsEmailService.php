<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\helpers\{HashHelper,
    MailHelper};
use app\widgets\EmailMailingWidget;

/**
 * Отправляет Email сообщение об удачной подписке
 */
class MailingsEmailService extends AbstractBaseService
{
    /**
     * @var string email для отправки сообщения
     */
    private $email;
    /**
     * @var array MailingsModel
     */
    private $mailingsArray;
    
    /**
     * Обрабатывает запрос на отправку сообщения
     */
    public function get()
    {
        try {
            if (empty($this->email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            if (empty($this->mailingsArray)) {
                throw new ErrorException($this->emptyError('mailingsArray'));
            }
            
            $html = EmailMailingWidget::widget([
                'mailings'=>$this->mailingsArray,
                'email'=>$this->email,
                'key'=>HashHelper::createHash([$this->email]),
                'template'=>'email-mailings-subscribe-success.twig'
            ]);
            
            $mailHelper = new MailHelper([
                [
                    'from'=>['admin@shop.com'=>'Shop.com'], 
                    //'to'=>$email,
                    'to'=>'timofey@localhost',
                    'subject'=>\Yii::t('base', 'Your subscription to shop.com'),
                    'html'=>$html
                ]
            ]);
            $sent = $mailHelper->send();
            
            if ($sent !== 1) {
                throw new ErrorException($this->methodError('sendEmail'));
            }
            
            return $sent;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение MailingsEmailService::email
     * @param string $email
     */
    public function setEmail(string $email)
    {
        try {
            $this->email = $email;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение MailingsEmailService::mailingsArray
     * @param array $mailingsArray
     */
    public function setMailingsArray(array $mailingsArray)
    {
        try {
            $this->mailingsArray = $mailingsArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

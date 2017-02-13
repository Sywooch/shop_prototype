<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\helpers\MailHelper;
use app\widgets\EmailRegistrationWidget;

/**
 * Отправляет Email сообщение об удачной регистрации
 */
class RegistrationEmailService extends AbstractBaseService
{
    /**
     * @var string email для отправки сообщения
     */
    private $email;
    
    /**
     * Обрабатывает запрос на отправку сообщения
     */
    public function get()
    {
        try {
            if (empty($this->email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            $html = EmailRegistrationWidget::widget([
                'email'=>$this->email, 
                'template'=>'registration-mail.twig'
            ]);
            
            $mailHelper = new MailHelper([
                [
                    'from'=>['admin@shop.com'=>'Shop.com'], 
                    //'to'=>$this->email,
                    'to'=>'timofey@localhost',
                    'subject'=>\Yii::t('base', 'Registration on shop.com'),
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
     * Присваивает значение RegistrationEmailService::email
     */
    public function setEmail(string $email)
    {
        try {
            $this->email = $email;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

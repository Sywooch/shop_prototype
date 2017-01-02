<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\helpers\MailHelper;

/**
 * Отправляет Email сообщение об удачной регистрации
 */
class RegistrationEmailService extends AbstractBaseService
{
    /**
     * @var array данные для EmailRegistrationWidget
     */
    private $emailRegistrationArray = [];
    /**
     * @var string email регистрируемого пользователя
     */
    private $email = null;
    
    /**
     * Обрабатывает запрос на отправку сообщения
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($request['email'])) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            $this->email = $request['email'];
            
            $mailHelper = new MailHelper([
                [
                    'from'=>['admin@shop.com'=>'Shop'], 
                    'to'=>['timofey@localhost'=>'Timofey'], 
                    'subject'=>\Yii::t('base', 'Registration on shop.com'), 
                    'template'=>'@theme/mail/registration-mail.twig',
                    'templateData'=>['letterConfig'=>$this->getEmailRegistrationArray()],
                ]
            ]);
            $sent = $mailHelper->send();
            
            if ($sent !== 1) {
                throw new ErrorException($this->methodError('sendEmail'));
            }
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета EmailRegistrationWidget
     * @return array
     */
    private function getEmailRegistrationArray(): array
    {
        try {
            if (empty($this->emailRegistrationArray)) {
                $dataArray = [];
                
                $dataArray['email'] = $this->email;
                $dataArray['view'] = 'registration-mail.twig';
                
                $this->emailRegistrationArray = $dataArray;
            }
            
            return $this->emailRegistrationArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

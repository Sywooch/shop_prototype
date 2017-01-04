<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\helpers\MailHelper;

/**
 * Отправляет Email сообщение содержащее ссылку для смены пароля
 */
class RecoveryEmailService extends AbstractBaseService
{
    /**
     * @var array данные для EmailRecoveryWidget
     */
    private $emailRecoveryArray = [];
    /**
     * @var string ключ
     */
    private $key = null;
    /**
     * @var string email
     */
    private $email = null;
    
    /**
     * Обрабатывает запрос на отправку сообщения
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($request['key'])) {
                throw new ErrorException($this->emptyError('key'));
            }
            if (empty($request['email'])) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            $this->key = $request['key'];
            $this->email = $request['email'];
            
            $mailHelper = new MailHelper([
                [
                    'from'=>['admin@shop.com'=>'Shop.com'], 
                    //'to'=>$this->email,
                    'to'=>'timofey@localhost',
                    'subject'=>\Yii::t('base', 'Password recovery from shop.com'), 
                    'template'=>'@theme/mail/recovery-mail.twig',
                    'templateData'=>['letterConfig'=>$this->getEmailRecoveryArray()],
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
     * Возвращает массив конфигурации для виджета EmailRecoveryWidget
     * @return array
     */
    private function getEmailRecoveryArray(): array
    {
        try {
            if (empty($this->emailRecoveryArray)) {
                $dataArray = [];
                
                $dataArray['key'] = $this->key;
                $dataArray['email'] = $this->email;
                $dataArray['view'] = 'recovery-mail.twig';
                
                $this->emailRecoveryArray = $dataArray;
            }
            
            return $this->emailRecoveryArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetEmailRecoveryWidgetConfigService};
use app\helpers\MailHelper;
use app\widgets\EmailRecoveryWidget;

/**
 * Отправляет Email сообщение содержащее ссылку для смены пароля
 */
class RecoveryEmailService extends AbstractBaseService
{
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
     */
    public function get()
    {
        try {
            if (empty($this->key)) {
                throw new ErrorException($this->emptyError('key'));
            }
            if (empty($this->email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            /*$service = \Yii::$app->registry->get(GetEmailRecoveryWidgetConfigService::class);
            $emailRecoveryWidgetConfig = $service->handle([
                'key'=>$key,
                'email'=>$email
            ]);*/
            
            $html = EmailRecoveryWidget::widget([
                'key'=>$this->key,
                'email'=>$this->email, 
                'template'=>'recovery-mail.twig'
            ]);
            
            $mailHelper = new MailHelper([
                [
                    'from'=>['admin@shop.com'=>'Shop.com'], 
                    //'to'=>$email,
                    'to'=>'timofey@localhost',
                    'subject'=>\Yii::t('base', 'Password recovery from shop.com'), 
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
     * Присваивает значение RecoveryEmailService::key
     * @param string $key
     */
    public function setKey(string $key)
    {
        try {
            $this->key = $key;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение RecoveryEmailService::email
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
}

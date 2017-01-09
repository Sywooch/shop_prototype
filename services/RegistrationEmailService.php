<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetEmailRegistrationWidgetConfigService};
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
     * Обрабатывает запрос на отправку сообщения
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $email = $request['email'] ?? null;
            
            if (empty($email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            $service = \Yii::$app->registry->get(GetEmailRegistrationWidgetConfigService::class);
            $emailRegistrationWidgetArray = $service->handle([
                'email'=>$email
            ]);
            
            $mailHelper = new MailHelper([
                [
                    'from'=>['admin@shop.com'=>'Shop.com'], 
                    //'to'=>$email,
                    'to'=>'timofey@localhost',
                    'subject'=>\Yii::t('base', 'Registration on shop.com'),
                    'template'=>'@theme/mail/registration-mail.twig',
                    'templateData'=>['letterConfig'=>$emailRegistrationWidgetArray],
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
}

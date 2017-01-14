<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetEmailReceivedOrderWidgetConfigService};
use app\helpers\MailHelper;

/**
 * Отправляет Email сообщение об удачной регистрации
 */
class ReceivedOrderEmailService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на отправку сообщения
     * @param array $request
     */
    public function handle($request=null)
    {
        try {
            $service = \Yii::$app->registry->get(GetEmailReceivedOrderWidgetConfigService::class);
            $emailReceivedOrderWidgetArray = $service->handle();
            
            $mailHelper = new MailHelper([
                [
                    'from'=>['admin@shop.com'=>'Shop.com'], 
                    //'to'=>$email,
                    'to'=>'timofey@localhost',
                    'subject'=>\Yii::t('base', 'Information about your order'),
                    'template'=>'@theme/mail/received-order-mail.twig',
                    'templateData'=>['letterConfig'=>$emailReceivedOrderWidgetArray],
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

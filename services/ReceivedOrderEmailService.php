<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetEmailReceivedOrderWidgetConfigService};
use app\helpers\MailHelper;
use app\widgets\EmailReceivedOrderWidget;

/**
 * Отправляет Email сообщение об удачной регистрации
 */
class ReceivedOrderEmailService extends AbstractBaseService
{
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
            
            $service = \Yii::$app->registry->get(GetEmailReceivedOrderWidgetConfigService::class);
            $emailReceivedOrderWidgetArray = $service->handle();
            
            $mailHelper = new MailHelper([
                [
                    'from'=>['admin@shop.com'=>'Shop.com'], 
                    //'to'=>$email,
                    'to'=>'timofey@localhost',
                    'subject'=>\Yii::t('base', 'Information about your order'),
                    'html'=>EmailReceivedOrderWidget::widget($emailReceivedOrderWidgetArray)
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

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
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $key = $request['key'] ?? null;
            $email = $request['email'] ?? null;
            
            if (empty($key)) {
                throw new ErrorException($this->emptyError('key'));
            }
            if (empty($email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            $service = \Yii::$app->registry->get(GetEmailRecoveryWidgetConfigService::class);
            $emailRecoveryWidgetConfig = $service->handle([
                'key'=>$key,
                'email'=>$email
            ]);
            
            $mailHelper = new MailHelper([
                [
                    'from'=>['admin@shop.com'=>'Shop.com'], 
                    //'to'=>$email,
                    'to'=>'timofey@localhost',
                    'subject'=>\Yii::t('base', 'Password recovery from shop.com'), 
                    'html'=>EmailRecoveryWidget::widget($emailRecoveryWidgetConfig)
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

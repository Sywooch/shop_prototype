<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetEmailMailingWidgetConfigService};
use app\helpers\MailHelper;
use app\widgets\EmailMailingWidget;

/**
 * Отправляет Email сообщение об удачной подписке
 */
class MailingsEmailService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на отправку сообщения
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $email = $request['email'] ?? null;
            $diffIdArray = $request['diffIdArray'] ?? null;
            
            if (empty($email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            if (empty($diffIdArray)) {
                throw new ErrorException($this->emptyError('diffIdArray'));
            }
            
            $service = \Yii::$app->registry->get(GetEmailMailingWidgetConfigService::class);
            $emailMailingWidgetArray = $service->handle([
                'diffIdArray'=>$diffIdArray
            ]);
            
            $mailHelper = new MailHelper([
                [
                    'from'=>['admin@shop.com'=>'Shop.com'], 
                    //'to'=>$email,
                    'to'=>'timofey@localhost',
                    'subject'=>\Yii::t('base', 'Your subscription to shop.com'),
                    'html'=>EmailMailingWidget::widget($emailMailingWidgetArray)
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

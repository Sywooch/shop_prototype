<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив данных для MailingsUnsubscribeEmptyWidget
 */
class GetMailingsUnsubscribeEmptyWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array данные для MailingsUnsubscribeEmptyWidget
     */
    private $mailingsUnsubscribeEmptyWidgetArray = [];
    
    /**
     * Возвращает массив данных для MailingsUnsubscribeEmptyWidget
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $email = $request->get(\Yii::$app->params['emailKey']);
            
            if (empty($email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            if (empty($this->mailingsUnsubscribeEmptyWidgetArray)) {
                $dataArray = [];
                
                $dataArray['email'] = $email;
                $dataArray['view'] = 'unsubscribe-empty.twig';
                
                $this->mailingsUnsubscribeEmptyWidgetArray = $dataArray;
            }
            
            return $this->mailingsUnsubscribeEmptyWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

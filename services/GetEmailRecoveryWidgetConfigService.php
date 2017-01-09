<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает объект текущей валюты
 */
class GetEmailRecoveryWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета EmailRecoveryWidget
     */
    private $emailRecoveryWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета EmailRecoveryWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->emailRecoveryWidgetArray)) {
                $key = $request['key'] ?? null;
                $email = $request['email'] ?? null;
                
                if (empty($key)) {
                    throw new ErrorException($this->emptyError('key'));
                }
                if (empty($email)) {
                    throw new ErrorException($this->emptyError('email'));
                }
                
                $dataArray = [];
                
                $dataArray['key'] = $key;
                $dataArray['email'] = $email;
                $dataArray['view'] = 'recovery-mail.twig';
                
                $this->emailRecoveryWidgetArray = $dataArray;
            }
            
            return $this->emailRecoveryWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

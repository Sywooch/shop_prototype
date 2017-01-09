<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает объект текущей валюты
 */
class GetEmailRegistrationWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета EmailRegistrationWidget
     */
    private $emailRegistrationWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета EmailRegistrationWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->emailRegistrationWidgetArray)) {
                $email = $request['email'] ?? null;
                
                if (empty($email)) {
                    throw new ErrorException($this->emptyError('email'));
                }
                
                $dataArray = [];
                
                $dataArray['email'] = $email;
                $dataArray['view'] = 'registration-mail.twig';
                
                $this->emailRegistrationWidgetArray = $dataArray;
            }
            
            return $this->emailRegistrationWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

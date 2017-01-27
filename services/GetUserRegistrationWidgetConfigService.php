<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\forms\UserRegistrationForm;

/**
 * Возвращает массив конфигурации для виджета UserRegistrationWidget
 */
class GetUserRegistrationWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета UserRegistrationWidget
     */
    private $userRegistrationWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета UserRegistrationWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->userRegistrationWidgetArray)) {
                $dataArray = [];
                
                $dataArray['form'] = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
                $dataArray['header'] = \Yii::t('base', 'Registration');
                $dataArray['template'] = 'registration-form.twig';
                
                $this->userRegistrationWidgetArray = $dataArray;
            }
            
            return $this->userRegistrationWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

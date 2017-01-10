<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета UserRegistrationSuccessWidget
 */
class GetUserRegistrationSuccessWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета UserRegistrationSuccessWidget
     */
    private $гserRegistrationSuccessWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета UserRegistrationSuccessWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->гserRegistrationSuccessWidgetArray)) {
                $dataArray = [];
                
                $dataArray['view'] = 'registration-success.twig';
                
                $this->гserRegistrationSuccessWidgetArray = $dataArray;
            }
            
            return $this->гserRegistrationSuccessWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

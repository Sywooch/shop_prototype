<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\forms\UserLoginForm;

/**
 * Возвращает массив конфигурации для виджета UserLoginWidget
 */
class GetUserLoginWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета UserLoginWidget
     */
    private $userLoginWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета UserLoginWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->userLoginWidgetArray)) {
                $dataArray = [];
                
                $dataArray['form'] = new UserLoginForm(['scenario'=>UserLoginForm::LOGIN]);
                $dataArray['view'] = 'login-form.twig';
                
                $this->userLoginWidgetArray = $dataArray;
            }
            
            return $this->userLoginWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\forms\UserChangePasswordForm;

/**
 * Возвращает массив конфигурации для виджета AccountChangePasswordWidget
 */
class GetAccountChangePasswordWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AccountChangePasswordWidget
     */
    private $accountChangePasswordWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->accountChangePasswordWidgetArray)) {
                $dataArray = [];
                
                $dataArray['form'] = new UserChangePasswordForm(['scenario'=>UserChangePasswordForm::CHANGE]);
                $dataArray['view'] = 'account-change-password-form.twig';
                
                $this->accountChangePasswordWidgetArray = $dataArray;
            }
            
            return $this->accountChangePasswordWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

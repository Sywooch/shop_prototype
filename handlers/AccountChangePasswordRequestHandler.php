<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\forms\UserChangePasswordForm;

/**
 * Обрабатывает запрос данных 
 * для рендеринга страницы с формой смены пароля
 */
class AccountChangePasswordRequestHandler extends AbstractBaseHandler
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $dataArray = [];
                
                $dataArray['accountChangePasswordWidgetConfig'] = $this->accountChangePasswordWidgetConfig();
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountChangePasswordWidget
     * @return array
     */
    private function accountChangePasswordWidgetConfig()
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Change password');
            $dataArray['form'] = new UserChangePasswordForm(['scenario'=>UserChangePasswordForm::CHANGE]);
            $dataArray['template'] = 'account-change-password-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

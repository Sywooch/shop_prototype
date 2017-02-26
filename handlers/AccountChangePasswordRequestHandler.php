<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\UserChangePasswordForm;

/**
 * Обрабатывает запрос данных 
 * для рендеринга страницы с формой смены пароля
 */
class AccountChangePasswordRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
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
                
                $userChangePasswordForm = new UserChangePasswordForm(['scenario'=>UserChangePasswordForm::CHANGE]);
                
                $dataArray['accountChangePasswordWidgetConfig'] = $this->accountChangePasswordWidgetConfig($userChangePasswordForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

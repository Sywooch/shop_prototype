<?php

namespace app\services;

use app\services\{AbstractBaseService,
    FrontendTrait};
use app\forms\UserLoginForm;

/**
 * Формирует массив данных для рендеринга страницы формы аутентификации
 */
class UserLoginFormService extends AbstractBaseService
{
    use FrontendTrait;
    
    /**
     * @var array данные для UserLoginWidget
     */
    private $userLoginArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML формы аутентификации
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            $dataArray = [];
            
            $dataArray['userConfig'] = $this->getUserArray();
            $dataArray['cartConfig'] = $this->getCartArray();
            $dataArray['currencyConfig'] = $this->getCurrencyArray();
            $dataArray['searchConfig'] = $this->getSearchArray();
            $dataArray['menuConfig'] = $this->getCategoriesArray();
            
            $dataArray['formConfig'] = $this->getUserLoginArray();
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserLoginWidget
     * @return array
     */
    private function getUserLoginArray(): array
    {
        try {
            if (empty($this->userLoginArray)) {
                $dataArray = [];
                
                $dataArray['form'] = new UserLoginForm();
                $dataArray['view'] = 'login-form.twig';
                
                $this->userLoginArray = $dataArray;
            }
            
            return $this->userLoginArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

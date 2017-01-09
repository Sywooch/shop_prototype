<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCartWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetCurrencyWidgetConfigService,
    GetSearchWidgetConfigService,
    GetUserInfoWidgetConfigService,
    GetUserLoginWidgetConfigService};

/**
 * Формирует массив данных для рендеринга страницы с формой аутентификации
 */
class UserLoginFormService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML формы аутентификации
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $dataArray = [];
            
            $service = new GetUserLoginWidgetConfigService();
            $dataArray['userLoginWidgetConfig'] = $service->handle();
            
            $service = new GetUserInfoWidgetConfigService();
            $dataArray['userInfoWidgetConfig'] = $service->handle();
            
            $service = new GetCartWidgetConfigService();
            $dataArray['cartWidgetConfig'] = $service->handle();
            
            $service = new GetCurrencyWidgetConfigService();
            $dataArray['currencyWidgetConfig'] = $service->handle();
            
            $service = new GetSearchWidgetConfigService();
            $dataArray['searchWidgetConfig'] = $service->handle($request);
            
            $service = new GetCategoriesMenuWidgetConfigService();
            $dataArray['categoriesMenuWidgetConfig'] = $service->handle();
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

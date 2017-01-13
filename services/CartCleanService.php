<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use app\services\{AbstractBaseService,
    GetShortCartWidgetAjaxConfigService};
use app\helpers\HashHelper;
use app\cleaners\SessionCleaner;
use app\widgets\ShortCartWidget;

/**
 * Сохраняет новую покупку в корзине
 */
class CartCleanService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на сохранение новой покупки в корзине
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            if ($request->isAjax === true) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
                $cleaner = new SessionCleaner([
                    'keys'=>[HashHelper::createCartKey(), HashHelper::createCartCustomerKey()],
                ]);
                $cleaner->clean();
                
                
                $service = \Yii::$app->registry->get(GetShortCartWidgetAjaxConfigService::class);
                $shortCartWidgetAjaxConfig = $service->handle();
                    
                $cartInfo = ShortCartWidget::widget($shortCartWidgetAjaxConfig);
            
                return $cartInfo;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

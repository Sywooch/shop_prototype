<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use app\services\{AbstractBaseService,
    GetCartCheckoutWidgetConfigService};
use app\widgets\CartCheckoutWidget;

/**
 * Возвращает форму оформления заказа
 */
class CartCheckoutAjaxFormService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на создание формы оформления заказа
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if ($request->isAjax === true) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
                $service = \Yii::$app->registry->get(GetCartCheckoutWidgetConfigService::class);
                $cartCheckoutWidgetConfig = $service->handle();
                
                return CartCheckoutWidget::widget($cartCheckoutWidgetConfig);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

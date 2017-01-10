<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use app\services\{AbstractBaseService,
    GetCartWidgetAjaxService};
use app\helpers\HashHelper;
use app\cleaners\SessionCleaner;

/**
 * Сохраняет новую покупку в корзине
 */
class PurchaseCleanService extends AbstractBaseService
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
                    'keys'=>[HashHelper::createCartKey()],
                ]);
                $cleaner->clean();
                
                $service = \Yii::$app->registry->get(GetCartWidgetAjaxService::class);
                $cartInfo = $service->handle();
            
                return $cartInfo;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

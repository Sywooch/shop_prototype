<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use app\handlers\{AbstractBaseHandler,
    BaseHandlerTrait,
    CartHandlerTrait};
use app\helpers\HashHelper;
use app\cleaners\SessionCleaner;
use app\widgets\ShortCartWidget;
use app\finders\PurchasesSessionFinder;

/**
 * Обрабатывает запрос на удаление покупок из корзины
 */
class CartCleanRequestHandler extends AbstractBaseHandler
{
    use BaseHandlerTrait, CartHandlerTrait;
    
    /**
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
                
                $currentCurrencyModel = $this->getCurrentCurrency();
                
                $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                    'key'=>HashHelper::createCartKey()
                ]);
                $purchasesCollection = $finder->find();
                
                $shortCartWidgetAjaxConfig = $this->shortCartWidgetAjaxConfig($purchasesCollection, $currentCurrencyModel);
                return ShortCartWidget::widget($shortCartWidgetAjaxConfig);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

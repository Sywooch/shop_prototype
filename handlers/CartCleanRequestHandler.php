<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\helpers\HashHelper;
use app\removers\SessionRemover;
use app\widgets\ShortCartWidget;
use app\finders\PurchasesSessionFinder;
use app\services\GetCurrentCurrencyModelService;

/**
 * Обрабатывает запрос на удаление покупок из корзины
 */
class CartCleanRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            if ($request->isAjax === true) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
                }
                
                $remover = new SessionRemover([
                    'keys'=>[HashHelper::createCartKey(), HashHelper::createCartCustomerKey()],
                ]);
                $remover->remove();
                
                $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                    'key'=>HashHelper::createCartKey()
                ]);
                $ordersCollection = $finder->find();
                if (empty($ordersCollection)) {
                    throw new ErrorException($this->emptyError('ordersCollection'));
                }
                
                $shortCartWidgetAjaxConfig = $this->shortCartWidgetAjaxConfig($ordersCollection, $currentCurrencyModel);
                return ShortCartWidget::widget($shortCartWidgetAjaxConfig);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

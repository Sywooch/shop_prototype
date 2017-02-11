<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\handlers\AbstractBaseHandler;
use app\helpers\HashHelper;
use app\cleaners\SessionCleaner;

/**
 * Обрабатывает запрос на удаление покупок из корзины, 
 * возвращает URL для редиректа
 */
class CartCleanRedirectRequestHandler extends AbstractBaseHandler
{
    /**
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            if ($request->isPost === true) {
                $cleaner = new SessionCleaner([
                    'keys'=>[HashHelper::createCartKey(), HashHelper::createCartCustomerKey()],
                ]);
                $cleaner->clean();
                
                return Url::to(['/products-list/index']);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

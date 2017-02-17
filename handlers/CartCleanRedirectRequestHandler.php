<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\handlers\AbstractBaseHandler;
use app\helpers\HashHelper;
use app\removers\SessionRemover;

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
                $remover = new SessionRemover([
                    'keys'=>[HashHelper::createCartKey(), HashHelper::createCartCustomerKey()],
                ]);
                $remover->remove();
                
                return Url::to(['/products-list/index']);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

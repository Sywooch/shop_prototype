<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\services\AbstractBaseService;
use app\helpers\HashHelper;
use app\cleaners\SessionCleaner;

/**
 * Очищает корзину и возвращает URL для редиректа
 */
class CartCleanRedirectService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на обнуление корзины
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            if ($request->isPost === true) {
                $cleaner = new SessionCleaner([
                    'keys'=>[HashHelper::createCartKey()],
                ]);
                $cleaner->clean();
                
                return Url::to(['/products-list/index']);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

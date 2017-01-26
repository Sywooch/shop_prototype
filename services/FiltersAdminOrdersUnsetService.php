<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\services\AbstractBaseService;
use app\helpers\HashHelper;
use app\cleaners\SessionCleaner;

/**
 * Обнуляет фильтры заказов админ раздела
 */
class FiltersAdminOrdersUnsetService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на обнуление фильтров заказов
     * @param array $request
     * @return string URL
     */
    public function handle($request=null): string
    {
        try {
            $cleaner = new SessionCleaner([
                'keys'=>[HashHelper::createHash([\Yii::$app->params['adminOrdersFilters']])],
            ]);
            $cleaner->clean();
            
            return Url::to(['/admin/orders']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

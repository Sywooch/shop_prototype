<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\services\AbstractBaseService;
use app\filters\AdminOrdersFilters;
use app\finders\AdminOrdersSessionFinder;
use app\helpers\HashHelper;

/**
 * Возвращает объект товарных фильтров
 */
class GetAdminOrdersFiltersModelService extends AbstractBaseService
{
    /**
     * @var AdminOrdersFilters
     */
    private $filtersModel = null;
    
    /**
     * Возвращает AdminOrdersFilters
     * @param $request
     * @return AdminOrdersFilters
     */
    public function handle($request=null): AdminOrdersFilters
    {
        try {
            if (empty($this->filtersModel)) {
                $finder = \Yii::$app->registry->get(AdminOrdersSessionFinder::class, ['key'=>HashHelper::createHash([\Yii::$app->params['adminOrdersFilters']])]);
                $filtersModel = $finder->find();
                
                if (empty($filtersModel)) {
                    throw new ErrorException($this->emptyError('filtersModel'));
                }
                
                $this->filtersModel = $filtersModel;
            }
            
            return $this->filtersModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

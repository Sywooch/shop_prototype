<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\services\AbstractBaseService;
use app\filters\ProductsFilters;
use app\finders\FiltersSessionFinder;
use app\helpers\HashHelper;

/**
 * Возвращает объект товарных фильтров
 */
class GetProductsFiltersModelServiceAjax extends AbstractBaseService
{
    /**
     * @var ProductsFilters
     */
    private $filtersModel = null;
    
    /**
     * Возвращает ProductsFilters
     * @param $request
     * @return ProductsFilters
     */
    public function handle($request): ProductsFilters
    {
        try {
            $key = $request['key'] ?? null;
            
            if (empty($key)) {
                throw new ErrorException($this->emptyError('key'));
            }
            
            if (empty($this->filtersModel)) {
                $finder = \Yii::$app->registry->get(FiltersSessionFinder::class, ['key'=>HashHelper::createFiltersKey($key)]);
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

<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\services\AbstractBaseService;
use app\filters\ProductsFilters;
use app\finders\ProductsFiltersSessionFinder;
use app\helpers\HashHelper;

/**
 * Возвращает объект товарных фильтров
 */
class GetProductsFiltersModelService extends AbstractBaseService
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
    public function handle($request=null): ProductsFilters
    {
        try {
            if (empty($this->filtersModel)) {
                $finder = \Yii::$app->registry->get(ProductsFiltersSessionFinder::class, [
                    'key'=>HashHelper::createFiltersKey(Url::current())
                ]);
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

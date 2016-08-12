<?php

namespace app\queries;

use app\queries\{AbstractFiltersQueryCreator,
    ProductsListQueryCreator};

/**
 * Конструирует запрос к БД для получения списка строк
 */
class SizesJoinProductsQueryCreator extends AbstractFiltersQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'tableOne'=>[
            'firstTableName'=>'sizes',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_sizes',
            'secondTableFieldOn'=>'id_sizes',
        ],
        'tableTwo'=>[
            'firstTableName'=>'products_sizes',
            'firstTableFieldOn'=>'id_products',
            'secondTableName'=>'products',
            'secondTableFieldOn'=>'id',
        ],
    ];
    
    public function init()
    {
        try {
            parent::init();
            
            $reflectionParent = new \ReflectionClass('app\queries\ProductsListQueryCreator');
            if ($reflectionParent->hasProperty('categoriesArrayFilters')) {
                $parentCategoriesArrayFilters = $reflectionParent->getProperty('categoriesArrayFilters')->getValue(new ProductsListQueryCreator);
            }
            $this->categoriesArrayFilters = array_merge($parentCategoriesArrayFilters, $this->categoriesArrayFilters);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

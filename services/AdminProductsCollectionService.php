<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\services\AbstractBaseService;
use app\finders\{AdminProductsFiltersSessionFinder,
    AdminProductsFinder};
use app\helpers\HashHelper;
use app\collections\ProductsCollection;

/**
 * Возвращает объект ProductsCollection
 */
class AdminProductsCollectionService extends AbstractBaseService
{
    /**
     * @var ProductsCollection
     */
    private $productsCollection = null;
    
    /**
     * Возвращает ProductsCollection
     * @param $request
     * @return ProductsCollection
     */
    public function handle($request): ProductsCollection
    {
        try {
            if (empty($this->productsCollection)) {
                $finder = \Yii::$app->registry->get(AdminProductsFiltersSessionFinder::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['adminProductsFilters']]),
                ]);
                $filtersModel = $finder->find();
                
                if (empty($filtersModel)) {
                    throw new ErrorException($this->emptyError('filtersModel'));
                }
                
                $finder = \Yii::$app->registry->get(AdminProductsFinder::class, [
                    'page'=>$request->get(\Yii::$app->params['pagePointer']) ?? 0,
                    'filters'=>$filtersModel
                ]);
                
                $this->productsCollection = $finder->find();
            }
            
            return $this->productsCollection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

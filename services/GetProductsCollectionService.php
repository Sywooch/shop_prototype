<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetProductsFiltersModelService};
use app\finders\ProductsFinder;
use app\collections\ProductsCollection;

/**
 * Возвращает объект ProductsCollection
 */
class GetProductsCollectionService extends AbstractBaseService
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
                $service = \Yii::$app->registry->get(GetProductsFiltersModelService::class);
                $filtersModel = $service->handle();
                
                $finder = \Yii::$app->registry->get(ProductsFinder::class, [
                    'category'=>$request->get(\Yii::$app->params['categoryKey']) ?? '',
                    'subcategory'=>$request->get(\Yii::$app->params['subcategoryKey']) ?? '',
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

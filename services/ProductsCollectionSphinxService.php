<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetProductsFiltersModelService,
    GetSphinxArrayService};
use app\finders\ProductsSphinxFinder;
use app\collections\CollectionInterface;

/**
 * Возвращает объект ProductsCollection
 */
class ProductsCollectionSphinxService extends AbstractBaseService
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
    public function handle($request): CollectionInterface
    {
        try {
            if (empty($this->productsCollection)) {
                $service = \Yii::$app->registry->get(GetProductsFiltersModelService::class);
                $filtersModel = $service->handle();
                
                $service = \Yii::$app->registry->get(GetSphinxArrayService::class);
                $sphinxArray = $service->handle($request);
                
                $finder = \Yii::$app->registry->get(ProductsSphinxFinder::class, [
                    'sphinx'=>$sphinxArray,
                    'page'=>(int) $request->get(\Yii::$app->params['pagePointer']) ?? 0,
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

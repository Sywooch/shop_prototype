<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetProductsFiltersModelService,
    GetSphinxArrayService};
use app\finders\ProductsSphinxFinder;
use app\collections\ProductsCollectionInterface;

/**
 * Возвращает объект ProductsCollection
 */
class GetProductsCollectionSphinxService extends AbstractBaseService
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
    public function handle($request): ProductsCollectionInterface
    {
        try {
            if (empty($this->productsCollection)) {
                $service = new GetProductsFiltersModelService();
                $filtersModel = $service->handle();
                
                $service = new GetSphinxArrayService();
                $sphinxArray = $service->handle($request);
                
                $finder = \Yii::$app->registry->get(ProductsSphinxFinder::class, [
                    'sphinx'=>$sphinxArray,
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

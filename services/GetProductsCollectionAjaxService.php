<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetProductsFiltersModelServiceAjax};
use app\finders\ProductsFinder;
use app\collections\ProductsCollection;

/**
 * Возвращает объект ProductsCollection
 */
class GetProductsCollectionAjaxService extends AbstractBaseService
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
            $key = $request['key'] ?? null;
            
            if (empty($key)) {
                throw new ErrorException($this->emptyError('key'));
            }
            
            if (empty($this->productsCollection)) {
                $service = \Yii::$app->registry->get(GetProductsFiltersModelServiceAjax::class);
                $filtersModel = $service->handle(['key'=>$key]);
                
                $finder = \Yii::$app->registry->get(ProductsFinder::class, [
                    'category'=>$request[\Yii::$app->params['categoryKey']] ?? null,
                    'subcategory'=>$request[\Yii::$app->params['subcategoryKey']] ?? null,
                    'page'=>$request[\Yii::$app->params['pagePointer']] ?? 0,
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

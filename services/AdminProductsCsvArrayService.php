<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\{AdminProductsCsvFinder,
    AdminProductsFiltersSessionFinder};
use app\helpers\HashHelper;

/**
 * Возвращает массив PurchasesModel
 */
class AdminProductsCsvArrayService extends AbstractBaseService
{
    /**
     * @var ActiveQuery
     */
    private $productsQuery = null;
    
    /**
     * Возвращает ActiveQuery
     * @param $request
     * @return ActiveQuery
     */
    public function handle($request=null)
    {
        try {
            if (empty($this->productsQuery)) {
                $finder = \Yii::$app->registry->get(AdminProductsFiltersSessionFinder::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['adminProductsFilters']]),
                ]);
                $filtersModel = $finder->find();
                
                $finder = \Yii::$app->registry->get(AdminProductsCsvFinder::class, [
                    'filters'=>$filtersModel
                ]);
                
                $this->productsQuery = $finder->find();
            }
            
            return $this->productsQuery;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\models\{CollectionInterface,
    CategoriesModel};
use app\services\SearchServiceInterface;

class CategoryOneSearchService extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
    /**
     * Обрабатывает запрос на поиск коллекции товаров
     * @param array $request
     * @return CollectionInterface
     */
    public function handle($seocode)
    {
        try {
            $query = CategoriesModel::find();
            $query->where(['[[seocode]]'=>$seocode]);
            $category = $query->one();
            
            return $category;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

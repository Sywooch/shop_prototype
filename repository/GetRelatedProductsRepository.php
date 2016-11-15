<?php

namespace app\repository;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\repository\GetGroupRepositoryInterface;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;

class GetRelatedProductsRepository implements GetGroupRepositoryInterface
{
    use ExceptionsTrait;
    
    private $items = [];
    
    public function getGroup($model): array
    {
        try {
            if (empty($this->items)) {
                $query = ProductsModel::find();
                $query->innerJoin('{{related_products}}', '[[related_products.id_related_product]]=[[products.id]]');
                $query->where(['[[related_products.id_product]]'=>$model->id]);
                $array = $query->all();
                if (!empty($array)) {
                    $this->items = $array;
                }
            }
            
            return $this->items;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

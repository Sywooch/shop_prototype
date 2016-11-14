<?php

namespace app\repository;

use yii\base\ErrorException;
use app\repository\GetOneRepositoryInterface;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;

class GetOneProductRepository implements GetOneRepositoryInterface
{
    use ExceptionsTrait;
    
    private $items = [];
    
    public function getOne($seocode)
    {
        try {
            if (array_key_exists($seocode, $this->items) !== true) {
                $model = ProductsModel::find()->where('seocode=:seocode', [':seocode'=>$seocode])->one();
                if ($model !== null) {
                    $this->items[$seocode] = $model;
                }
            }
            
            return !empty($this->items[$seocode]) ? $this->items[$seocode] : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

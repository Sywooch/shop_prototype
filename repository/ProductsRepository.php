<?php

namespace app\repository;

use app\repository\{BaseRepositoryInterface,
    ProductsRepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;
use app\helpers\HashHelper;

class ProductsRepository implements BaseRepositoryInterface, ProductsRepositoryInterface
{
    use ExceptionsTrait;
    
    private $items = [];
    
    public function getOneBySeocode(string $seocode)
    {
        try {
            $key = HashHelper::createHash([__METHOD__, $seocode]);
            if (array_key_exists($key, $this->items) !== true) {
                $model = ProductsModel::find()->where('seocode=:seocode', [':seocode'=>$seocode])->one();
                if ($model !== null) {
                    $this->items[$key] = $model;
                }
            }
            
            return isset($this->items[$key]) ? $this->items[$key] : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function save($item)
    {
        
    }
    
    public function update($item)
    {
        
    }
    
    public function delete($item)
    {
        
    }
    
    public function getById($id)
    {
        
    }
    
    public function getByIds(array $id)
    {
        
    }
}

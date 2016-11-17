<?php

namespace app\repository;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\repository\GetGroupRepositoryInterface;
use app\exceptions\ExceptionsTrait;
use app\models\CategoriesModel;

class CategoriesRepository implements GetGroupRepositoryInterface
{
    use ExceptionsTrait;
    
    private $items = [];
    
    public function getGroup($data=null): array
    {
        try {
            if (empty($this->items)) {
                $data = CategoriesModel::find()->with('subcategory')->all();
                if (!empty($data)) {
                    $this->items = $data;
                }
            }
            
            return $this->items;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

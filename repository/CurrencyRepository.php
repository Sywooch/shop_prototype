<?php

namespace app\repository;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\repository\{AbstractBaseRepository,
    GetGroupRepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\models\{CurrencyModel,
    QueryCriteriaInterface};

class CurrencyRepository extends AbstractBaseRepository implements GetGroupRepositoryInterface
{
    use ExceptionsTrait;
    
    private $items = [];
    
    public function getGroup($data=null): array
    {
        try {
            if (empty($this->items)) {
                $query = CurrencyModel::find();
                if (!empty($this->criteria)) {
                    $query = $this->criteria->filter($query);
                }
                $data = $query->all();
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

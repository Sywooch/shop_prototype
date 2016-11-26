<?php

namespace app\queries;

use yii\sphinx\Query;
use app\exceptions\ExceptionsTrait;
use app\models\SphinxModel;

class SphinxQuery extends Query
{
    use ExceptionsTrait;
    
    public function all($db=null)
    {
        try {
            $resultArray = parent::all($db=null);
            
            $objects = [];
            foreach ($resultArray as $item) {
                $objects[] = \Yii::createObject(array_merge(['class'=>SphinxModel::class], $item));
            }
            
            return $objects;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

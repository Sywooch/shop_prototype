<?php

namespace app\models;

use yii\base\Model;
use app\queries\SphinxQuery;
use app\exceptions\ExceptionsTrait;

class SphinxModel extends Model
{
    public $id;
    
    public static function find()
    {
        try {
            return new SphinxQuery();
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}

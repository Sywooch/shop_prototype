<?php

namespace app\repository;

use yii\base\ErrorException;
use app\repository\GetOneRepositoryInterface;
use app\exceptions\ExceptionsTrait;
use app\helpers\SessionHelper;
use app\models\CurrencyModel;

class CurrencySessionRepository implements GetOneRepositoryInterface
{
    use ExceptionsTrait;
    
    private $items = [];
    
    public function getOne($key): CurrencyModel
    {
        try {
            if (array_key_exists($key, $this->items) !== true) {
                $data = SessionHelper::read($key);
                if (!empty($data)) {
                    $this->items[$key] = \Yii::createObject(array_merge(['class'=>CurrencyModel::class], $data));
                }
            }
            
            return !empty($this->items[$key]) ? $this->items[$key] : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

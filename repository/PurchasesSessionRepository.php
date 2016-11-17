<?php

namespace app\repository;

use yii\base\ErrorException;
use app\repository\GetGroupRepositoryInterface;
use app\exceptions\ExceptionsTrait;
use app\helpers\SessionHelper;
use app\models\PurchasesModel;

class PurchasesSessionRepository implements GetGroupRepositoryInterface
{
    use ExceptionsTrait;
    
    private $items = [];
    
    public function getGroup($key)
    {
        try {
            if (array_key_exists($key, $this->items) !== true) {
                $data = SessionHelper::read($key);
                if (!empty($data)) {
                    foreach ($data as $purchase) {
                        $this->items[$key][] = \Yii::createObject(array_merge(['class'=>PurchasesModel::class], $purchase));
                    }
                }
            }
            
            return !empty($this->items[$key]) ? $this->items[$key] : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

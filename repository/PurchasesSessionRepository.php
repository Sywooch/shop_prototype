<?php

namespace app\repository;

use yii\base\ErrorException;
use app\repository\GetGroupRepositoryInterface;
use app\exceptions\ExceptionsTrait;
use app\helpers\SessionHelper;

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
                    $this->items[$key] = $data;
                }
            }
            
            return !empty($this->items[$key]) ? $this->items[$key] : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

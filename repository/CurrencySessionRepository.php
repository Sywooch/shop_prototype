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
    
    /**
     * @var object CurrencyModel
     */
    private $item;
    
    /**
     * Возвращает CurrencyModel, представляющий текущую валюту 
     * @param string $key ключ для поиска данных в сессионном хранилище
     * @return CurrencyModel или null
     */
    public function getOne($key): CurrencyModel
    {
        try {
            if (empty($this->item)) {
                $data = SessionHelper::read($key);
                if (!empty($data)) {
                    $this->item = \Yii::createObject(array_merge(['class'=>CurrencyModel::class], $data));
                }
            }
            
            return !empty($this->item) ? $this->item : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\helpers\SessionHelper;
use app\filters\AdminOrdersFilters;

/**
 * Возвращает AdminOrdersFilters из сессии
 */
class AdminOrdersFiltersSessionFinder extends AbstractBaseFinder
{
    /**
     * @var string key ключ доступа к данным
     */
    public $key;
    /**
     * @var AdminOrdersFilters
     */
    private $storage = null;
    
    /**
     * Возвращает данные из сессионного хранилища
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->key)) {
                throw new ErrorException($this->emptyError('key'));
            }
            
            if (empty($this->storage)) {
                $this->storage = new AdminOrdersFilters(['scenario'=>AdminOrdersFilters::SESSION]);
                
                $array = SessionHelper::read($this->key);
                if (!empty($array)) {
                    $this->storage->attributes = $array;
                }
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

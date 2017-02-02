<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\helpers\SessionHelper;
use app\filters\AdminProductsFilters;

/**
 * Возвращает коллекцию PurchasesModel из сессии
 */
class AdminProductsFiltersSessionFinder extends AbstractBaseFinder
{
    /**
     * @var string key ключ доступа к данным
     */
    private $key;
    /**
     * @var PurchasesCollection
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
                $this->storage = new AdminProductsFilters(['scenario'=>AdminProductsFilters::SESSION]);
                
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
    
    /**
     * Присваивает категорию свойству AdminProductsFiltersSessionFinder::key
     * @param string $key
     */
    public function setKey(string $key)
    {
        try {
            $this->key = $key;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\helpers\SessionHelper;
use app\collections\PurchasesCollection;
use app\models\PurchasesModel;

/**
 * Возвращает коллекцию PurchasesModel из сессии
 */
class PurchasesSessionFinder extends AbstractBaseFinder
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
                $this->storage = new PurchasesCollection();
                
                $array = SessionHelper::read($this->key);
                if (!empty($array)) {
                    foreach ($array as $data) {
                        $model = new PurchasesModel(['scenario'=>PurchasesModel::SESSION]);
                        $model->attributes = $data;
                        $this->storage->add($model);
                    }
                }
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает категорию свойству PurchasesSessionFinder::key
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

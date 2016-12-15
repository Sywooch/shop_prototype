<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\helpers\SessionHelper;
use app\models\CurrencyModel;

/**
 * Возвращает CurrencyModel текущей валюты из сессии
 */
class CurrencySessionFinder extends AbstractBaseFinder
{
    /**
     * @var string key ключ доступа к данным
     */
    public $key;
    /**
     * @var загруженный CurrencyModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из сессионного хранилища
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                if (empty($this->key)) {
                    throw new ErrorException($this->emptyError('key'));
                }
                
                $data = SessionHelper::read($this->key);
                
                if (!empty($data)) {
                    $model = new CurrencyModel(['scenario'=>CurrencyModel::DBMS]);
                    $model->attributes = $data;
                    $this->storage = $model;
                }
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

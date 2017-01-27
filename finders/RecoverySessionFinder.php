<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\helpers\SessionHelper;
use app\models\RecoveryModel;

/**
 * Возвращает RecoveryModel текущей валюты из сессии
 */
class RecoverySessionFinder extends AbstractBaseFinder
{
    /**
     * @var string key ключ доступа к данным
     */
    private $key;
    /**
     * @var загруженный RecoveryModel
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
                
                $data = SessionHelper::readFlash($this->key);
                
                if (!empty($data)) {
                    $model = new RecoveryModel(['scenario'=>RecoveryModel::SET]);
                    $model->attributes = $data;
                    $this->storage = $model;
                }
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает категорию свойству RecoverySessionFinder::key
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

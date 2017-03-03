<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\{AbstractBaseFinder,
    CurrencyIdFinder};
use app\helpers\SessionHelper;
use app\models\CurrencyModel;

/**
 * Возвращает CurrencyModel текущей валюты из сессии
 */
class CurrencySessionDBMSFinder extends AbstractBaseFinder
{
    /**
     * @var string key ключ доступа к данным
     */
    private $key;
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
            if (empty($this->key)) {
                throw new ErrorException($this->emptyError('key'));
            }
            
            if (empty($this->storage)) {
                $currencyArray = SessionHelper::read($this->key);
                $id = $currencyArray['id'];
                
                if (!empty($id)) {
                    $finder = \Yii::$app->registry->get(CurrencyIdFinder::class, [
                        'id'=>$id
                    ]);
                    $currencyModel = $finder->find();
                    if (!empty($currencyModel)) {
                        $this->storage = $currencyModel;
                    }
                }
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает категорию свойству CurrencySessionDBMSFinder::key
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

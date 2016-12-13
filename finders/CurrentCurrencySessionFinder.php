<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseSessionFinder;
use app\helpers\SessionHelper;
use app\collections\SessionCollectionInterface;
use app\models\CurrencyModel;

/**
 * Возвращает 1 элемент из сессии
 */
class CurrentCurrencySessionFinder extends AbstractBaseSessionFinder
{
    /**
     * @var string key ключ доступа к данным
     */
    public $key;
    
    private $storage;
    
    public function rules()
    {
        return [
            [['key'], 'required'],
        ];
    }
    
    /**
     * Возвращает данные из сессионного хранилища
     * @return SessionCollectionInterface
     */
    public function find()
    {
        try {
            /*if (empty($this->collection)) {
                throw new ErrorException($this->emptyError('collection'));
            }*/
            
            if (empty($this->storage)) {
                if ($this->validate() === false) {
                    throw new ErrorException($this->modelError($this->errors));
                }
                
                $data = SessionHelper::read($this->key);
                if (!empty($data)) {
                    $this->storage = new CurrencyModel($data);
                }
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

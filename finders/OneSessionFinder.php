<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseSessionFinder;
use app\helpers\SessionHelper;
use app\collections\SessionCollectionInterface;

/**
 * Возвращает 1 элемент из сессии
 */
class OneSessionFinder extends AbstractBaseSessionFinder
{
    /**
     * @var string key ключ доступа к данным
     */
    public $key;
    
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
    public function find(): SessionCollectionInterface
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException($this->emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                if ($this->validate() === false) {
                    throw new ErrorException($this->modelError($this->errors));
                }
                
                $data = SessionHelper::read($this->key);
                if (!empty($data)) {
                    $this->collection->addArray($data);
                }
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

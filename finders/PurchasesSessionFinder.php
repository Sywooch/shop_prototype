<?php

namespace app\finders;

use yii\base\{ErrorException,
    Model};
use app\finders\FinderInterface;
use app\exceptions\ExceptionsTrait;
use app\helpers\SessionHelper;
use app\models\PurchasesModel;
use app\collections\CollectionInterface;

class PurchasesSessionFinder extends Model implements FinderInterface
{
    use ExceptionsTrait;
    
    /**
     * @var string key ключ доступа к данным
     */
    public $key;
    /**
     * @var object CollectionInterface
     */
    private $collection;
    
    public function rules()
    {
        return [
            [['key'], 'required'],
        ];
    }
    
    /**
     * Загружает данные в свойства модели
     * @param $data массив данных
     * @return bool
     */
    public function load($data, $formName=null)
    {
        try {
            return parent::load($data, '');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные из сессионного хранилища
     * @return CollectionInterface
     */
    public function find(): CollectionInterface
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                if ($this->validate()) {
                    $data = SessionHelper::read($this->key);
                    if (!empty($data)) {
                        foreach ($data as $item) {
                            $this->collection->add(new PurchasesModel($item));
                        }
                    }
                }
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству PurchasesSessionFinder::collection
     * @param object $collection CollectionInterface
     */
    public function setCollection(CollectionInterface $collection)
    {
        try {
            $this->collection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

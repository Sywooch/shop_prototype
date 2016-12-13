<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseSessionFinder;
use app\helpers\SessionHelper;
use app\collections\PurchasesCollection;
use app\models\PurchasesModel;

/**
 * Возвращает коллекцию элементов из сессии
 */
class PurchasesSessionFinder extends AbstractBaseSessionFinder
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
                
                $this->storage = new PurchasesCollection();
                
                $array = SessionHelper::read($this->key);
                if (!empty($array)) {
                    foreach ($array as $data) {
                        $this->storage->add(new PurchasesModel($data));
                    }
                }
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

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
    public $key;
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
            if (empty($this->storage)) {
                if (empty($this->key)) {
                    throw new ErrorException($this->emptyError('key'));
                }
                
                $this->storage = new PurchasesCollection();
                
                $array = SessionHelper::read($this->key);
                if (!empty($array)) {
                    foreach ($array as $data) {
                        $model = new PurchasesModel(['scenario'=>PurchasesModel::SESSION_GET]);
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
}

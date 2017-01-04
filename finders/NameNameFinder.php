<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\NamesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает NamesModel из СУБД
 */
class NameNameFinder extends AbstractBaseFinder
{
    /**
     * @var string name
     */
    public $name;
    /**
     * @var NamesModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                if (empty($this->name)) {
                    throw new ErrorException($this->emptyError('name'));
                }
                
                $query = NamesModel::find();
                $query->select(['[[names.id]]', '[[names.name]]']);
                $query->where(['[[names.name]]'=>$this->name]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

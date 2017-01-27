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
    private $name;
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
            if (empty($this->name)) {
                throw new ErrorException($this->emptyError('name'));
            }
            
            if (empty($this->storage)) {
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
    
    /**
     * Присваивает имя свойству NameNameFinder::name
     * @param string $name
     */
    public function setName(string $name)
    {
        try {
            $this->name = $name;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

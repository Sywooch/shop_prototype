<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\MailingsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает из СУБД подписки, связанные с переданным email
 */
class MailingNameFinder extends AbstractBaseFinder
{
    /**
     * @var string name
     */
    private $name;
    /**
     * @var MailingsModel
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
                $query = MailingsModel::find();
                $query->select(['[[mailings.id]]', '[[mailings.name]]', '[[mailings.description]]', '[[mailings.active]]']);
                $query->where(['[[mailings.name]]'=>$this->name]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение MailingNameFinder::name
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

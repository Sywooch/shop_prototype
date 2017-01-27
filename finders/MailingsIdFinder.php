<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\MailingsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает подписки с указанными ID из СУБД
 */
class MailingsIdFinder extends AbstractBaseFinder
{
    /**
     * @var массив ID
     */
    private $id = [];
    /**
     * @var массив загруженных MailingsModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->id)) {
                throw new ErrorException($this->emptyError('id'));
            }
            
            if (empty($this->storage)) {
                $query = MailingsModel::find();
                $query->select(['[[mailings.id]]', '[[mailings.name]]', '[[mailings.description]]']);
                $query->where(['[[mailings.id]]'=>$this->id]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству MailingsIdFinder::id
     * @param array $id
     */
    public function setId(array $id)
    {
        try {
            $this->id = $id;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

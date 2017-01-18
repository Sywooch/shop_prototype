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
    public $id = [];
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
}

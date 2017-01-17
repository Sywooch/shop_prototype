<?php

namespace app\finders;

use app\models\MailingsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные подписки из СУБД
 */
class MailingsFinder extends AbstractBaseFinder
{
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
            if (empty($this->storage)) {
                $query = MailingsModel::find();
                $query->select(['[[mailings.id]]', '[[mailings.name]]', '[[mailings.description]]']);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\EmailsMailingsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает EmailsMailingsModel из СУБД
 */
class EmailsMailingsIdMailingFinder extends AbstractBaseFinder
{
    /**
     * @var int id_mailing
     */
    private $id_mailing;
    /**
     * @var array EmailsMailingsModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->id_mailing)) {
                throw new ErrorException($this->emptyError('id_mailing'));
            }
            
            if (empty($this->storage)) {
                $query = EmailsMailingsModel::find();
                $query->select(['[[emails_mailings.id_email]]', '[[emails_mailings.id_mailing]]']);
                $query->where(['[[emails_mailings.id_mailing]]'=>$this->id_mailing]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение EmailsMailingsIdMailingFinder::id_mailing
     * @param int $id_mailing
     */
    public function setId_mailing(int $id_mailing)
    {
        try {
            $this->id_mailing = $id_mailing;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

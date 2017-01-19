<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\MailingsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает из СУБД подписки, связанные с переданным email
 */
class MailingsEmailFinder extends AbstractBaseFinder
{
    /**
     * @var string email
     */
    public $email;
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
            if (empty($this->email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            if (empty($this->storage)) {
                $query = MailingsModel::find();
                $query->select(['[[mailings.id]]', '[[mailings.name]]', '[[mailings.description]]']);
                $query->innerJoin('{{emails_mailings}}', '[[mailings.id]]=[[emails_mailings.id_mailing]]');
                $query->innerJoin('{{emails}}', '[[emails_mailings.id_email]]=[[emails.id]]');
                $query->where(['[[emails.email]]'=>$this->email]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

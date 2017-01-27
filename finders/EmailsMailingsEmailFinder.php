<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\EmailsMailingsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает EmailsMailingsModel из СУБД
 */
class EmailsMailingsEmailFinder extends AbstractBaseFinder
{
    /**
     * @var string email
     */
    private $email;
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
            if (empty($this->email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            if (empty($this->storage)) {
                $query = EmailsMailingsModel::find();
                $query->select(['[[emails_mailings.id_email]]', '[[emails_mailings.id_mailing]]']);
                $query->innerJoin('{{emails}}', '[[emails_mailings.id_email]]=[[emails.id]]');
                $query->where(['[[emails.email]]'=>$this->email]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает email свойству EmailsMailingsEmailFinder::email
     * @param string $email
     */
    public function setEmail(string $email)
    {
        try {
            $this->email = $email;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\finders;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\models\MailingsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает из СУБД подписки, не связанные с переданным email
 */
class MailingsNotEmailFinder extends AbstractBaseFinder
{
    /**
     * @var string email
     */
    private $email;
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
                $existsQuery = MailingsModel::find();
                $existsQuery->select(['[[mailings.id]]']);
                $existsQuery->innerJoin('{{emails_mailings}}', '[[mailings.id]]=[[emails_mailings.id_mailing]]');
                $existsQuery->innerJoin('{{emails}}', '[[emails_mailings.id_email]]=[[emails.id]]');
                $existsQuery->where(['[[emails.email]]'=>$this->email]);
                
                $query = MailingsModel::find();
                $query->select(['[[mailings.id]]', '[[mailings.name]]', '[[mailings.description]]', '[[mailings.active]]']);
                $query->where(['not in', '[[mailings.id]]', $existsQuery]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает email свойству MailingsNotEmailFinder::email
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

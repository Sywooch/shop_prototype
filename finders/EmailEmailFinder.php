<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\EmailsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные категории товаров из СУБД
 */
class EmailEmailFinder extends AbstractBaseFinder
{
    /**
     * @var string email
     */
    private $email;
    /**
     * @var EmailsModel
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
                $query = EmailsModel::find();
                $query->select(['[[emails.id]]', '[[emails.email]]']);
                $query->where(['[[emails.email]]'=>$this->email]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает email свойству EmailEmailFinder::email
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

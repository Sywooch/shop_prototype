<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\UsersModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает пользователя с указанным email
 */
class UserEmailFinder extends AbstractBaseFinder
{
    /**
     * @var string email пользователя
     */
    public $email;
    /**
     * @var array найденный UsersModel
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
                if (empty($this->email)) {
                    throw new ErrorException($this->emptyError('email'));
                }
                
                $query = UsersModel::find();
                $query->select(['[[users.id]]', '[[users.id_email]]', '[[users.password]]', '[[users.id_name]]', '[[users.id_surname]]', '[[users.id_phone]]', '[[users.id_address]]', '[[users.id_city]]', '[[users.id_country]]', '[[users.id_postcode]]']);
                $query->innerJoin('{{emails}}', '[[users.id_email]]=[[emails.id]]');
                $query->where(['[[emails.email]]'=>$this->email]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

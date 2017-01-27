<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\UsersModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные категории товаров из СУБД
 */
class UserIdFinder extends AbstractBaseFinder
{
    /**
     * @var int Id пользователя
     */
    private $id;
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
                if (empty($this->id)) {
                    throw new ErrorException($this->emptyError('id'));
                }
                
                $query = UsersModel::find();
                $query->select(['[[users.id]]', '[[users.id_email]]', '[[users.password]]', '[[users.id_name]]', '[[users.id_surname]]', '[[users.id_phone]]', '[[users.id_address]]', '[[users.id_city]]', '[[users.id_country]]', '[[users.id_postcode]]']);
                $query->where(['[[users.id]]'=>$this->id]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ID свойству UserIdFinder::id
     * @param int $id
     */
    public function setId(int $id)
    {
        try {
            $this->id = $id;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

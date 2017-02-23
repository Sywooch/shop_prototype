<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\UsersModel;
use app\finders\AbstractBaseFinder;
use app\collections\{LightPagination,
    UsersCollection};

/**
 * Возвращает пользователя с указанным email
 */
class UsersFinder extends AbstractBaseFinder
{
    /**
     * @var string GET параметр, определяющий текущую страницу
     */
    private $page;
    /**
     * @var array UsersModel
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
                $this->storage = new UsersCollection(['pagination'=>new LightPagination()]);
                
                $query = UsersModel::find();
                $query->select(['[[users.id]]', '[[users.id_email]]', '[[users.id_name]]', '[[users.id_surname]]', '[[users.id_phone]]', '[[users.id_address]]', '[[users.id_city]]', '[[users.id_country]]', '[[users.id_postcode]]']);
                $query->with('email', 'name', 'surname', 'phone', 'address', 'city', 'country', 'postcode', 'orders');
                
                $this->storage->pagination->pageSize = \Yii::$app->params['limit'];
                $this->storage->pagination->page = !empty($this->page) ? (int) $this->page - 1 : 0;
                $this->storage->pagination->setTotalCount($query);
                
                $query->offset($this->storage->pagination->offset);
                $query->limit($this->storage->pagination->limit);
                
                $usersArray = $query->all();
                
                if (!empty($usersArray)) {
                    foreach ($usersArray as $user) {
                        $this->storage->add($user);
                    }
                }
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает номер страницы UsersFinder::page
     * @param int $page
     */
    public function setPage(int $page)
    {
        try {
            $this->page = $page;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

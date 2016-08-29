<?php

namespace app\mappers;

use yii\base\ErrorException;
use app\mappers\AbstractGetMapper;
use app\models\UsersModel;

/**
 * Получает строки с данными из БД, конструирует из каждой строки объект данных
 */
class PurchasesForUserMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\PurchasesByIdUsersQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\PurchasesObjectsFactory';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model) || !$this->model instanceof UsersModel || empty($this->model->id)) {
                throw new ErrorException('Не определен объект модели!');
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

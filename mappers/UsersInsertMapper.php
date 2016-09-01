<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;

/**
 * Добавляет записи в БД
 */
class UsersInsertMapper extends AbstractInsertMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\UsersInsertQueryCreator';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->objectsArray)) {
                throw new ErrorException('Не задан массив объектов!');
            }
            if (empty($this->fields)) {
                throw new ErrorException('Не заданы поля!');
            }
            
            foreach ($this->objectsArray as $object) {
                $data = array();
                foreach ($this->fields as $field) {
                    $data[] = $object->$field;
                }
                $this->params[] = $data;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

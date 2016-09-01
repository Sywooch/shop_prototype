<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;
use app\models\CommentsModel;

/**
 * Добавляет записи в БД
 */
class CommentsDeleteMapper extends AbstractInsertMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\CommentsDeleteQueryCreator';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->objectsArray)) {
                throw new ErrorException('Отсутствуют данные для построения запроса!');
            }
            
            foreach ($this->objectsArray as $object) {
                if (!is_object($object) || !$object instanceof CommentsModel) {
                    throw new ErrorException('Неверный тип данных!');
                }
                $this->params[] = $object->id;
            }
            
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

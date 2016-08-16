<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;
use app\models\SubcategoryModel;

/**
 * Добавляет записи в БД
 */
class SubcategoryDeleteMapper extends AbstractInsertMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\SubcategoryDeleteQueryCreator';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->objectsArray)) {
                throw new ErrorException('Отсутствуют данные для построения запроса!');
            }
            
            foreach ($this->objectsArray as $object) {
                if (!is_object($object) || !$object instanceof SubcategoryModel) {
                    throw new ErrorException('Неверный тип данных!');
                }
            }
            
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

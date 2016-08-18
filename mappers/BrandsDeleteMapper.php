<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;
use app\models\BrandsModel;

/**
 * Добавляет записи в БД
 */
class BrandsDeleteMapper extends AbstractInsertMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\BrandsDeleteQueryCreator';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->objectsArray)) {
                throw new ErrorException('Отсутствуют данные для построения запроса!');
            }
            
            foreach ($this->objectsArray as $object) {
                if (!is_object($object) || !$object instanceof BrandsModel) {
                    throw new ErrorException('Неверный тип данных!');
                }
            }
            
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

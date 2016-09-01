<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;
use app\models\ProductsColorsModel;

/**
 * Добавляет записи в БД
 */
class ProductsColorsDeleteMapper extends AbstractInsertMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\ProductsColorsDeleteQueryCreator';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->objectsArray)) {
                throw new ErrorException('Отсутствуют данные для построения запроса!');
            }
            
            foreach ($this->objectsArray as $object) {
                if (!is_object($object) || !$object instanceof ProductsColorsModel) {
                    throw new ErrorException('Неверный тип данных!');
                }
                $this->params[] = $object->id_products;
            }
            
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

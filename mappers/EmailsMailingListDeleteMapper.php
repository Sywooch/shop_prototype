<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;
use app\models\EmailsMailingListModel;

/**
 * Добавляет записи в БД
 */
class EmailsMailingListDeleteMapper extends AbstractInsertMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\EmailsMailingListDeleteQueryCreator';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->objectsArray)) {
                throw new ErrorException('Отсутствуют данные для построения запроса!');
            }
            
            foreach ($this->objectsArray as $object) {
                if (!is_object($object) || !$object instanceof EmailsMailingListModel) {
                    throw new ErrorException('Ошибка в переданных данных!');
                }
            }
            
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

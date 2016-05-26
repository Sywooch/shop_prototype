<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupMapper;
use yii\helpers\ArrayHelper;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class CategoriesMapper extends AbstractGetGroupMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\CategoriesQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\CategoriesObjectsFactory';
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        try {
            $command = \Yii::$app->db->createCommand($this->query);
            $result = $command->queryAll();
            ArrayHelper::multisort($result, ['name'], [SORT_ASC]);
            $this->DbArray = $result;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

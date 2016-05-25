<?php

namespace app\mappers;

use app\mappers\BaseAbstractMapper;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\queries\CategoriesQueryCreator;
use yii\helpers\ArrayHelper;
use app\factories\CategoriesObjectsFactory;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class CategoriesMapper extends BaseAbstractMapper
{
    use ExceptionsTrait;
    
    /**
     * Возвращает массив объектов, представляющий строки в БД
     * Класс CategoriesQueryCreator формирует строку запроса
     * Класс CategoriesObjectsFactory создает из данных БД объекты
     * @return array
     */
    public function getGroup()
    {
        try {
            $this->visit(new CategoriesQueryCreator());
            $this->getData();
            $this->visit(new CategoriesObjectsFactory());
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->objectsArray;
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    private function getData()
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

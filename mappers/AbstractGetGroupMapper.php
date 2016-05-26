<?php

namespace app\mappers;

use app\mappers\AbstractBaseMapper;
use yii\helpers\ArrayHelper;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
abstract class AbstractGetGroupMapper extends AbstractBaseMapper
{
    /**
     * Передает классу-визитеру объект для дополнительной обработки данных
     * @param object объект класса-визитера
     */
    public function visit($visitor)
    {
        try {
            $visitor->update($this);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив объектов, представляющих строки в БД
     * @return array
     */
    public function getGroup()
    {
        try {
            if (!isset($this->queryClass)) {
                throw new ErrorException('Не задано имя класа, формирующего строку!');
            }
            $this->visit(new $this->queryClass());
            $this->getData();
            if (!isset($this->objectsClass)) {
                throw new ErrorException('Не задано имя класа, который создает объекты из данных БД!');
            }
            $this->visit(new $this->objectsClass());
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->objectsArray;
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        try {
            $command = \Yii::$app->db->createCommand($this->query);
            $result = $command->queryAll();
            ArrayHelper::multisort($result, [$this->orderByField], [SORT_ASC]);
            $this->DbArray = $result;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

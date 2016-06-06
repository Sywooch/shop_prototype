<?php

namespace app\mappers;

use app\mappers\AbstractBaseMapper;
use yii\base\ErrorException;

/**
 * Реализует вставку 1 строки данных в БД
 */
abstract class AbstractInsertMapper extends AbstractBaseMapper
{
    /**
     * Формирует запрос к БД и выполняет его
     */
    protected function run()
    {
        try {
            if (!isset($this->queryClass)) {
                throw new ErrorException('Не задано имя класа, формирующего строку!');
            }
            $this->visit(new $this->queryClass());
            
            $result = $this->setData();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает 1 запись в БД
     * @return boolean
     */
    public function setOne()
    {
        try {
            $this->run();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return true;
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function setData()
    {
        try {
            $command = \Yii::$app->db->createCommand($this->query);
            $bindArray = $this->getBindArray();
            if (!empty($bindArray)) {
                $command->bindValues($bindArray);
            }
            $result = $command->execute();
            if (!$result) {
                throw new ErrorException('Не удалось обновить данные в БД!');
            }
            if (YII_DEBUG) {
                $this->trigger($this::SENT_REQUESTS_TO_DB); # Фиксирует выполнение запроса к БД
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует агрегированный массив данных для привязки к запросу
     */
    protected function getBindArray()
    {
        $result = array();
        try {
            if (isset($this->objectsOne)) {
                foreach ($this->fields as $field) {
                    $result[':' . $field] = $this->objectsOne->$field;
                }
            } else {
                throw new ErrorException('Отсутсвуют данные для выполнения запроса!');
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $result;
    }
}

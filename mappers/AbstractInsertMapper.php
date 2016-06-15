<?php

namespace app\mappers;

use app\mappers\AbstractBaseMapper;
use yii\base\ErrorException;

/**
 * Реализует вставку строк данных в БД
 */
abstract class AbstractInsertMapper extends AbstractBaseMapper
{
    /**
     * @var array массив ключей и значений для подстановки в строку запроса,
     * заполняется в процессе работы queryCreator
     */
    public $insertData = array();
    
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
            
            $this->setData();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает группу записей в БД
     * @return boolean
     */
    public function setGroup()
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
        try {
            if (isset($this->objectsArray)) {
                return $this->insertData;
            } else {
                throw new ErrorException('Отсутсвуют данные для выполнения запроса!');
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

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
     * Формирует запрос к БД и выполняет его
     */
    protected function run()
    {
        try {
            if (!empty($this->DbArray)) {
                if (empty($this->objectsClass)) {
                    throw new ErrorException('Не задано имя класа, формирующего объекты!');
                }
                if (!$this->visit(new $this->objectsClass())) {
                    throw new ErrorException('Ошибка при вызове конструктора объектов!');
                }
            }
            
            if (empty($this->queryClass)) {
                throw new ErrorException('Не задано имя класа, формирующего строку!');
            }
            if (!$this->visit(new $this->queryClass())) {
                throw new ErrorException('Ошибка при вызове конструктора запроса к БД!');
            }
            
            if (!$result = $this->setData()) {
                return false;
            }
            return $result;
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
            if (!$result = $this->run()) {
                return false;
            }
            return $result;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function setData()
    {
        try {
            if (empty($this->query)) {
                throw new ErrorException('Не определена строка запроса к БД!');
            }
            
            $command = \Yii::$app->db->createCommand($this->query);
            if (!empty($this->params)) {
                $command->bindValues($this->params);
            }
            $result = $command->execute();
            if (YII_DEBUG) {
                $this->trigger($this::SENT_REQUESTS_TO_DB); # Фиксирует выполнение запроса к БД
            }
            if (!$result) {
                return false;
            }
            return $result;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

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
            if (!empty($this->params)) {
                $command->bindValues($this->params);
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
}

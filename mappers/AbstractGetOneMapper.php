<?php

namespace app\mappers;

use app\mappers\AbstractBaseMapper;
use yii\base\ErrorException;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
abstract class AbstractGetOneMapper extends AbstractBaseMapper
{
    /**
     * @var array массив данных для подстановки в запрос
     */
    public $params = array();
    
    /**
     * Возвращает массив объектов, представляющих строки в БД
     * @return array
     */
    public function getOne()
    {
        try {
            $this->run();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->objectsOne;
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        try {
            $command = \Yii::$app->db->createCommand($this->query);
            $command->bindValues($this->params);
            $result = $command->queryOne();
            if (YII_DEBUG) {
                $this->trigger($this::SENT_REQUESTS_TO_DB); # Фиксирует выполнение запроса к БД
            }
            $this->DbArray = $result;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

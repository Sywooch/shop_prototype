<?php

namespace app\mappers;

use app\mappers\AbstractBaseMapper;
use yii\helpers\ArrayHelper;
use yii\base\ErrorException;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
abstract class AbstractGetMapper extends AbstractBaseMapper
{
    /**
     * Возвращает массив объектов, представляющих строки в БД
     * @return array
     */
    public function getGroup()
    {
        try {
            if (!$this->run()) {
                throw new ErrorException('Ошибка при выполнении метода run!');
            }
            return $this->objectsArray;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает 1 объект, представляющий строку в БД, является надстройкой над AbstractGetMapper::getGroup()
     * @return object
     */
    public function getOneFromGroup()
    {
        try {
            $groupArray = $this->getGroup();
            if (!is_array($groupArray)) {
                throw new ErrorException('Ожидался массив объектов, получен не массив!');
            }
            if (count($groupArray) > 1) {
                throw new ErrorException('Ожидался 1 объект, получено более 1 объекта');
            } elseif (empty($groupArray)) {
                return false;
            }
            return $groupArray[0];
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return boolean
     */
    protected function getData()
    {
        try {
            if (empty($this->query)) {
                throw new ErrorException('Не определена строка запроса к БД!');
            }
            $command = \Yii::$app->db->createCommand($this->query);
            if (!empty($this->params)) {
                $command->bindValues($this->params);
            }
            $result = $command->queryAll();
            if (YII_DEBUG) {
                $this->trigger($this::SENT_REQUESTS_TO_DB); # Фиксирует выполнение запроса к БД
            }
            if ($this->getDataSorting) {
                ArrayHelper::multisort($result, [$this->orderByField], [SORT_ASC]);
            }
            $this->DbArray = $result;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

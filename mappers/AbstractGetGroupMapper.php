<?php

namespace app\mappers;

use app\mappers\AbstractBaseMapper;
use yii\helpers\ArrayHelper;
use yii\base\ErrorException;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
abstract class AbstractGetGroupMapper extends AbstractBaseMapper
{
    /**
     * Возвращает массив объектов, представляющих строки в БД
     * @return array
     */
    public function getGroup()
    {
        try {
            $this->run();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->objectsArray;
    }
    
    /**
     * Возвращает 1 объект, представляющий строку в БД, является надстройкой над AbstractGetGroupMapper::getGroup()
     * @return object
     */
    public function getOneFromGroup()
    {
        try {
            $groupArray = $this->getGroup();
            if (count($groupArray) > 1) {
                throw new ErrorException('Ожидался 1 объект, получено более 1 объекта');
            } elseif (empty($groupArray)) {
                return false;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $groupArray[0];
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        try {
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
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

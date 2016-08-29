<?php

namespace app\mappers;

use yii\helpers\ArrayHelper;
use yii\base\ErrorException;
use app\mappers\AbstractBaseMapper;

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
                return false;
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
            if (!is_array($groupArray) || empty($groupArray)) {
                return false;
            }
            if (count($groupArray) > 1) {
                throw new ErrorException('Ожидался 1 объект, получено более 1 объекта');
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
            
            $result = $this->query->all();
            
            if (YII_DEBUG) {
                $this->trigger($this::SENT_REQUESTS_TO_DB);
            }
            
            if ($this->getDataSorting) {
                ArrayHelper::multisort($result, [$this->orderByField], [($this->orderByType && $this->orderByType == 'DESC') ? SORT_DESC : SORT_ASC]);
            }
            
            $this->DbArray = $result;
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

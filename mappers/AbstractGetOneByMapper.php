<?php

namespace app\mappers;

use app\mappers\AbstractGetOneMapper;
use yii\base\ErrorException;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
abstract class AbstractGetOneByMapper extends AbstractGetOneMapper
{
    /**
     * @var object объект, свойство которого содержит Email для выполнения запроса
     */
    public $model;
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        try {
            $paramBindKey = $this->paramBindKey;
            if (!isset($this->model)) {
                throw new ErrorException('Не передана модель!');
            }
            parent::getData();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupMapper;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;

/**
 * Обеспечивает функциональность, общую для классов GetGroupForProductMapper
 */
abstract class AbstractGetGroupFromModelMapper extends AbstractGetGroupMapper
{
    /**
     * @var object объект модели, из которой берутся данные для получения объектов
     */
    public $model;
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        try {
            if (!isset($this->model)) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            parent::getData();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

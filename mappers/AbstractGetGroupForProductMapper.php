<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupMapper;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;

/**
 * Обеспечивает функциональность, общую для классов GetGroupForProductMapper
 */
abstract class AbstractGetGroupForProductMapper extends AbstractGetGroupMapper
{
    /**
     * @var object объект модели, представляющей строку таблицы products, для которой необходимо получить связанные объекты
     */
    public $model;
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        try {
            if (!isset($this->model) || !isset($this->model->id)) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            $command = \Yii::$app->db->createCommand($this->query);
            $command->bindValue(':' . \Yii::$app->params['idKey'], $this->model->id);
            $result = $command->queryAll();
            if (YII_DEBUG) {
                $this->trigger($this::SENT_REQUESTS_TO_DB); # Фиксирует выполнение запроса к БД
            }
            ArrayHelper::multisort($result, $this->orderByField, [SORT_ASC]);
            $this->DbArray = $result;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

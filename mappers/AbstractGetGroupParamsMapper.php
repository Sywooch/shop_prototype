<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupMapper;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;

/**
 * Обеспечивает функциональность, общую для классов GetGroupForProductMapper
 */
abstract class AbstractGetGroupParamsMapper extends AbstractGetGroupMapper
{
    /**
     * @var array массив данных для подстановки в запрос
     */
    public $params = array();
    /**
     * @var object объект модели, из которой берутся данные для получения объектов
     */
    public $model;
    
    public function init()
    {
        parent::init();
        
        if (empty($this->params)) {
            $this->params = [':' . \Yii::$app->params['idKey']=>$this->model->id];
        }
    }
    
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
            $command = \Yii::$app->db->createCommand($this->query);
            $command->bindValues($this->params);
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

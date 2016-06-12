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
     * @var string имя ключа и переменной для $command->bindValue
     */
    public $paramBindKey;
    /**
     * @var string значение ключа для $command->bindValue
     */
    public $paramBindKeyValue;
    /**
     * @var object объект модели, представляющей строку таблицы products, для которой необходимо получить связанные объекты
     */
    public $model;
    
    public function init()
    {
        parent::init();
        
        if (!isset($this->paramBindKey)) {
            $this->paramBindKey = \Yii::$app->params['idKey'];
        }
        
        if (!isset($this->paramBindKeyValue)) {
            $propertyName = \Yii::$app->params['idKey'];
            $this->paramBindKeyValue = $this->model->$propertyName;
        }
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        try {
            $paramBindKey = $this->paramBindKey;
            if (!isset($this->model)) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            $command = \Yii::$app->db->createCommand($this->query);
            $command->bindValue(':' . $this->paramBindKey, $this->paramBindKeyValue);
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

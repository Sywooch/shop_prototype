<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupForProductMapper;
use yii\helpers\ArrayHelper;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class SimilarProductsMapper extends AbstractGetGroupForProductMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\SimilarProductsQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\ProductsObjectsFactory';
    /**
     * @var array массив фильтров для привязки к запросу
     */
    public $filtersArray = array();
    
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
            $bindArray = $this->getBindArray();
            if (!empty($bindArray)) {
                $command->bindValues($bindArray);
            }
            $this->DbArray = $command->queryAll();
            if (YII_DEBUG) {
                $this->trigger($this::SENT_REQUESTS_TO_DB); # Фиксирует выполнение запроса к БД
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует агрегированный массив данных для привязки к запросу
     */
    private function getBindArray()
    {
        $result = array();
        try {
            $result[':' . \Yii::$app->params['idKey']] = $this->model->id;
            $result[':' . \Yii::$app->params['categoryKey']] = \Yii::$app->request->get(\Yii::$app->params['categoryKey']);
            $result[':' . \Yii::$app->params['subCategoryKey']] = \Yii::$app->request->get(\Yii::$app->params['subCategoryKey']);
            $result = array_merge($result, $this->filtersArray);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $result;
    }
}

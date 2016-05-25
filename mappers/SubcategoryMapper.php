<?php

namespace app\mappers;

use app\mappers\BaseAbstractMapper;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\queries\SubcategoryQueryCreator;
use app\factories\SubcategoryObjectsFactory;

class SubcategoryMapper extends BaseAbstractMapper
{
    use ExceptionsTrait;
    
    /**
     * @var object объект модели, представляющей таблицу category, для которой необходимо получить связанные объекты из subcategory
     */
    public $categoriesModel;
    
    /**
     * Возвращает массив объектов, представляющий строки в БД
     * Класс SubcategoryQueryCreator формирует строку запроса
     * Класс SubcategoryObjectsFactory создает из данных БД объекты
     * @return array
     */
    public function getGroup()
    {
        try {
            $this->visit(new SubcategoryQueryCreator());
            $this->getData();
            $this->visit(new SubcategoryObjectsFactory());
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->objectsArray;
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    private function getData()
    {
        try {
            $command = \Yii::$app->db->createCommand($this->query);
            $command->bindValue(':' . \Yii::$app->params['categoryKey'], $this->categoriesModel->id);
            $this->DbArray = $command->queryAll();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

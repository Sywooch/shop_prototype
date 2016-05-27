<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupMapper;
use yii\helpers\ArrayHelper;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class SubcategoryMapper extends AbstractGetGroupMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\SubcategoryQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\SubcategoryObjectsFactory';
    /**
     * @var object объект модели, представляющей таблицу category, для которой необходимо получить связанные объекты из subcategory
     */
    public $categoriesModel;
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        try {
            if (!isset($this->categoriesModel) || !isset($this->categoriesModel->id)) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить подкатегории!');
            }
            $command = \Yii::$app->db->createCommand($this->query);
            $command->bindValue(':' . \Yii::$app->params['categoryKey'], $this->categoriesModel->id);
            $result = $command->queryAll();
            if (YII_DEBUG) {
                $this->trigger($this::SENT_REQUESTS_TO_DB); # Фиксирует выполнение запроса к БД
            }
            ArrayHelper::multisort($result, ['name'], [SORT_ASC]);
            $this->DbArray = $result;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

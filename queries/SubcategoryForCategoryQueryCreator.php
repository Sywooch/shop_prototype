<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class SubcategoryForCategoryQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'categories'=>[
            'firstTableName'=>'subcategory',
            'firstTableFieldOn'=>'id_categories',
            'secondTableName'=>'categories',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'id',
        ],
    ];
    
    /**
     * Инициирует создание SELECT запроса
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            
            if (!parent::getSelectQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $join = $this->getJoin(
                $this->config[\Yii::$app->params['categoryKey']]['firstTableName'],
                $this->config[\Yii::$app->params['categoryKey']]['firstTableFieldOn'],
                $this->config[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->config[\Yii::$app->params['categoryKey']]['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            $where = $this->getWhere(
                $this->config[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->config[\Yii::$app->params['categoryKey']]['secondTableFieldWhere'],
                \Yii::$app->params['idKey']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

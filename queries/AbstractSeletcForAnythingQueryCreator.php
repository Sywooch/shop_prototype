<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Предоставляет функциональность, общую для всех классов SeletcForProductQueryCreator
 */
abstract class AbstractSeletcForAnythingQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * Инициирует создание SELECT запроса
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (empty(\Yii::$app->params['idKey'])) {
                throw new ErrorException('Не поределен idKey!');
            }
            
            if (!parent::getSelectQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $join = $this->getJoin(
                $this->config[\Yii::$app->params['idKey']]['firstTableName'],
                $this->config[\Yii::$app->params['idKey']]['firstTableFieldOn'],
                $this->config[\Yii::$app->params['idKey']]['secondTableName'],
                $this->config[\Yii::$app->params['idKey']]['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            $where = $this->getWhere(
                $this->config[\Yii::$app->params['idKey']]['secondTableName'],
                $this->config[\Yii::$app->params['idKey']]['secondTableFieldWhere'],
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

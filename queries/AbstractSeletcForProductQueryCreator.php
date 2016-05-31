<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Предоставляет функциональность, общую для всех классов SeletcForProductQueryCreator
 */
abstract class AbstractSeletcForProductQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * Инициирует создание SELECT запроса
     */
    public function getSelectQuery()
    {
        try {
            parent::getSelectQuery();
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableFieldOn']
            );
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableFieldWhere'],
                \Yii::$app->params['idKey']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

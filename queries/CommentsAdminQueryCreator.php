<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class CommentsAdminQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * Инициирует создание SELECT запроса
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (!parent::getSelectQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            if (empty(\Yii::$app->filters->getActive) || empty(\Yii::$app->filters->getNotActive)) {
                $filter = null;
                if (!empty(\Yii::$app->filters->getActive)) {
                    $filter = true;
                } elseif (!empty(\Yii::$app->filters->getNotActive)) {
                    $filter = false;
                }
                if (!is_null($filter)) {
                    $this->_mapperObject->query->where(['comments.active'=>$filter]);
                }
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

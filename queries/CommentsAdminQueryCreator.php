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
     * @var array массив данных для построения запроса
     */
    public $config = [
        'comments'=>[
            'tableName'=>'comments',
            'tableFieldWhere'=>'active',
        ],
    ];
    
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
                $filterActive = null;
                if (!empty(\Yii::$app->filters->getActive)) {
                    $filterActive = true;
                } elseif (!empty(\Yii::$app->filters->getNotActive)) {
                    $filterActive = false;
                }
                if (!is_null($filterActive)) {
                    $where = $this->getWhere(
                        $this->config['comments']['tableName'],
                        $this->config['comments']['tableFieldWhere'],
                        $this->config['comments']['tableFieldWhere']
                    );
                    if (!is_string($where)) {
                        throw new ErrorException('Ошибка при построении запроса!');
                    }
                    $this->_mapperObject->query .= $where;
                    $this->_mapperObject->params[':' . $this->config['comments']['tableFieldWhere']] = $filterActive;
                }
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

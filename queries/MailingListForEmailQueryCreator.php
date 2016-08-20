<?php

namespace app\queries;

use yii\helpers\ArrayHelper;
use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class MailingListForEmailQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'mailing_list'=>[
            'firstTableName'=>'mailing_list',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'emails_mailing_list',
            'secondTableFieldOn'=>'id_mailing_list',
        ],
        'emails'=>[
            'firstTableName'=>'emails_mailing_list',
            'firstTableFieldOn'=>'id_email',
            'secondTableName'=>'emails',
            'secondTableFieldOn'=>'id',
        ],
        'emails_where'=>[
            'tableName'=>'emails',
            'tableField'=>'email',
            'tableFieldWhere'=>'email'
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
            
            $join = $this->getJoin(
                $this->config['mailing_list']['firstTableName'],
                $this->config['mailing_list']['firstTableFieldOn'],
                $this->config['mailing_list']['secondTableName'],
                $this->config['mailing_list']['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            $join = $this->getJoin(
                $this->config['emails']['firstTableName'],
                $this->config['emails']['firstTableFieldOn'],
                $this->config['emails']['secondTableName'],
                $this->config['emails']['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            
            $where = $this->getWhere(
                $this->config['emails_where']['tableName'],
                $this->config['emails_where']['tableField'],
                $this->config['emails_where']['tableFieldWhere']
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

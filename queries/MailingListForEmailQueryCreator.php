<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;
use yii\helpers\ArrayHelper;
use yii\base\ErrorException;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class MailingListForEmailQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных с учетом категории или(и) подкатегории, а также фильтров
     */
    public $categoriesArrayFilters = [
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
                $this->categoriesArrayFilters['mailing_list']['firstTableName'],
                $this->categoriesArrayFilters['mailing_list']['firstTableFieldOn'],
                $this->categoriesArrayFilters['mailing_list']['secondTableName'],
                $this->categoriesArrayFilters['mailing_list']['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            $join = $this->getJoin(
                $this->categoriesArrayFilters['emails']['firstTableName'],
                $this->categoriesArrayFilters['emails']['firstTableFieldOn'],
                $this->categoriesArrayFilters['emails']['secondTableName'],
                $this->categoriesArrayFilters['emails']['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            
            $where = $this->getWhere(
                $this->categoriesArrayFilters['emails_where']['tableName'],
                $this->categoriesArrayFilters['emails_where']['tableField'],
                $this->categoriesArrayFilters['emails_where']['tableFieldWhere']
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

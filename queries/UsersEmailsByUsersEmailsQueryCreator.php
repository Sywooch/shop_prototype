<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class UsersEmailsByUsersEmailsQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'id_users'=>[ # Данные для выборки из таблицы users_emails
            'tableName'=>'users_emails', # Имя таблицы участвующей в объединении
            'tableFieldWhere'=>'id_users', # Имя поля таблицы, по которому делается выборка с помощью WHERE
        ],
        'id_emails'=>[ # Данные для выборки из таблицы users_emails
            'tableName'=>'users_emails', # Имя таблицы участвующей в объединении
            'tableFieldWhere'=>'id_emails', # Имя поля таблицы, по которому делается выборка с помощью WHERE
        ],
    ];
    
    /**
     * Инициирует создание SELECT запроса
     */
    public function getSelectQuery()
    {
        try {
            parent::getSelectQuery();
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters['id_users']['tableName'],
                $this->categoriesArrayFilters['id_users']['tableFieldWhere'],
                $this->categoriesArrayFilters['id_users']['tableFieldWhere']
            );
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters['id_emails']['tableName'],
                $this->categoriesArrayFilters['id_emails']['tableFieldWhere'],
                $this->categoriesArrayFilters['id_emails']['tableFieldWhere']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

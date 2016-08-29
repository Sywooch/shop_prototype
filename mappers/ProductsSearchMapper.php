<?php

namespace app\mappers;

use yii\base\ErrorException;
use app\mappers\AbstractGetMapper;

/**
 * Получает строки с данными о товарах из БД, конструирует из каждой строки объект данных
 */
class ProductsSearchMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\ProductsSphynxQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\ProductsObjectsFactory';
    /**
     * @var array массив конфигурации для выполнения запроса к sphynx
     */
    public $dbConfig = [
        'class'=>'yii\db\Connection',
        'dsn'=>'mysql:host=127.0.0.1;port=9306;dbname=shop',
        'username'=>'shopadmin',
        'password'=>'shopadmin',
        'charset'=>'utf8',
    ];
    /**
     * @var object объект yii\db\Connection для выполнения запроса к sphynx
     */
    private $_db;
    
    public function init()
    {
        try {
            parent::init();
            
            $this->_db = \Yii::createObject($this->dbConfig);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return boolean
     */
    protected function getData()
    {
        try {
            if (empty($this->query)) {
                throw new ErrorException('Не определена строка запроса к БД!');
            }
            
            $result = $this->query->all($this->_db);
            
            if (YII_DEBUG) {
                $this->trigger($this::SENT_REQUESTS_TO_DB);
            }
            
            $this->DbArray = $result;
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

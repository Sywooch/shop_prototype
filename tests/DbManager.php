<?php

namespace app\tests;

use yii\test\FixtureTrait;
use yii\base\Object;
use yii\base\ErrorException;

/**
 * Управляет созданием и удалением тестовой БД
 */
class DbManager extends Object
{
    use FixtureTrait;
    
    /**
     * @var string логин к учетной записи с правами создания и удаления тестовой БД
     */
    public $login = 'root';
    /**
     * @var string пароль к учетной записи с правами создания и удаления тестовой БД
     */
    public $password = 'estet234';
    /**
     * @var string имя тестовой БД, которая будет создана
     */
    public $testDbName = 'shop_test';
    /**
     * @var array фикстуры, которые будут использоваться в текущем TestCase
     */
    public $fixtures = array();
    /**
     * @var string хэш созданной БД
     */
    private $_dbHash;
    /**
     * @var array массив объектов фикстур, используемых текущим тестом,
     * ключ - псевдоним, назначенный в конфигурации
     */
    private $_fixturesData = array();
    
    public function init()
    {
        parent::init();
        
        $this->escapeArgs();
        
        if (!empty($this->fixtures)) {
            foreach (array_keys($this->fixtures) as $key) {
                $this->_fixturesData[$key] = $this->getFixture($key);
            }
        }
    }
    
    /**
     * Возвращает объект фикстуры, в случае, если параметр $name 
     * соответствует одному из ключей в $this->_fixturesData
     * @param string $name имя ключа
     * @return object/null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_fixturesData)) {
            return $this->_fixturesData[$name];
        }
        
        return null;
    }
    
    /**
     * Возвращает фикстуры, которые будут использоваться в текущем TestCase
     * @return array
     */
    public function fixtures()
    {
        return $this->fixtures;
    }
    
    /**
     * Создает тестовую БД
     */
    public function createDb()
    {
        try {
            $this->_dbHash = md5($this->login . $this->password . $this->testDbName);
            
            $cmd = sprintf("mysql -u%s -p%s -e 'CREATE DATABASE IF NOT EXISTS %s'", $this->login, $this->password, $this->testDbName);
            exec($cmd);
            
            $cmd = "/var/www/html/shop/yii migrate --interactive=0";
            exec($cmd);
            
            $this->loadFixtures();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при создании тестовой БД!\n" . $e->getMessage());
        }
    }
    
    /**
     * Удаляет тестовую БД
     */
    public function deleteDb()
    {
        try {
            if (!empty($this->_dbHash) && $this->_dbHash == md5($this->login . $this->password . $this->testDbName)) {
                $this->unloadFixtures();
                
                $cmd = sprintf("mysql -u%s -p%s -e 'DROP DATABASE %s'", $this->login, $this->password, $this->testDbName);
                exec($cmd);
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при удалении тестовой БД!\n" . $e->getMessage());
        }
    }
    
    /**
     * Экранирует аргументы
     */
    private function escapeArgs()
    {
        try {
            $this->login = escapeshellcmd(escapeshellarg($this->login));
            $this->password = escapeshellcmd(escapeshellarg($this->password));
            $this->testDbName = escapeshellcmd(escapeshellarg($this->testDbName));
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при экранировании аргументов!\n" . $e->getMessage());
        }
    }
}

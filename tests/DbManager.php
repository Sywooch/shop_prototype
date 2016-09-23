<?php

namespace app\tests;

use yii\test\FixtureTrait;
use yii\base\Object;
use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

/**
 * Управляет созданием и удалением тестовой БД
 */
class DbManager extends Object
{
    use FixtureTrait, ExceptionsTrait;
    
    /**
     * @var array фикстуры, которые будут использоваться в текущем TestCase
     */
    public $fixtures = array();
    /**
     * @var array массив объектов фикстур, используемых текущим тестом,
     * ключ - псевдоним, назначенный в конфигурации
     */
    private $_fixturesData = array();
    
    public function init()
    {
        try {
            parent::init();
            
            if (!empty($this->fixtures)) {
                foreach (array_keys($this->fixtures) as $key) {
                    $this->_fixturesData[$key] = $this->getFixture($key);
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
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
        try {
            if (array_key_exists($name, $this->_fixturesData)) {
                return $this->_fixturesData[$name];
            }
            
            return null;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает фикстуры, которые будут использоваться в текущем TestCase
     * @return array
     */
    public function fixtures()
    {
        try {
            return $this->fixtures;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

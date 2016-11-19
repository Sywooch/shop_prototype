<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\repository\CurrencyRepository;
use app\models\{AbstractBaseComposit,
    CurrencyCompositInterface,
    CurrencyModel,
    QueryCriteria};

class CurrencyRepositoryTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод CurrencyRepository::setItems
     * передаю не поддерживающий интерфейс CurrencyCompositInterface объект
     * @expectedException TypeError
     */
    public function testSetItemsError()
    {
        $repository = new CurrencyRepository();
        $repository->items = new class () {};
    }
    
    /**
     * Тестирует метод CurrencyRepository::getGroup
     * вызываю с пустым CurrencyRepository::items
     * @expectedException yii\base\ErrorException
     */
    public function testGetGroupError()
    {
        $repository = new CurrencyRepository();
        $repository->getGroup();
    }
    
    /**
     * Тестирует метод CurrencyRepository::getGroup
     */
    public function testGetGroup()
    {
        $repository = new CurrencyRepository();
        $repository->items = new class () extends AbstractBaseComposit implements CurrencyCompositInterface {
            public $items = [];
            public function add(CurrencyModel $model) {
                $this->items[] = $model;
            }
            public function isEmpty() 
            {
                return empty($this->items) ? true : false;
            }
        };
        $result = $repository->getGroup();
        
        $this->assertTrue($result instanceof CurrencyCompositInterface);
        foreach ($result as $object) {
            $this->assertTrue($object instanceof CurrencyModel);
        }
    }
    
    /**
     * Тестирует метод CurrencyRepository::getGroup
     * с применением критериев выборки
     */
    public function testGetGroupCriteria()
    {
        $repository = new CurrencyRepository();
        $repository->items = new class () extends AbstractBaseComposit implements CurrencyCompositInterface {
            public $items = [];
            public function add(CurrencyModel $model) {
                $this->items[] = $model;
            }
            public function isEmpty() 
            {
                return empty($this->items) ? true : false;
            }
        };
        $criteria = new QueryCriteria();
        $criteria->where(['>', '[[currency.id]]', 1]);
        $repository->setCriteria($criteria);
        $result = $repository->getGroup();
        
        $this->assertTrue($result instanceof CurrencyCompositInterface);
        foreach ($result as $object) {
            $this->assertTrue($object instanceof CurrencyModel);
        }
    }
    
    /**
     * Тестирует метод CurrencyRepository::getGroup
     * при отсутствии данных, удовлетворяющих условиям SQL запроса
     */
    public function testGetGroupCriteriaNull()
    {
        $repository = new CurrencyRepository();
        $repository->items = new class () extends AbstractBaseComposit implements CurrencyCompositInterface {
            public $items = [];
            public function add(CurrencyModel $model) {
                $this->items[] = $model;
            }
            public function isEmpty() 
            {
                return empty($this->items) ? true : false;
            }
        };
        $criteria = new QueryCriteria();
        $criteria->where(['[[currency.id]]'=>[100,230]]);
        $repository->setCriteria($criteria);
        $result = $repository->getGroup();
        
        $this->assertNull($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}

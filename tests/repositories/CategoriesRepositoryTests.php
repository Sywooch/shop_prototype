<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\sources\fixtures\{
    CategoriesFixture,
    SubcategoryFixture};
use app\repository\CategoriesRepository;
use app\models\{AbstractBaseComposit,
    CategoriesCompositInterface,
    CategoriesModel,
    QueryCriteria,
    SubcategoryModel};

class CategoriesRepositoryTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод CategoriesRepository::setItems
     * передаю не поддерживающий интерфейс CategoriesCompositInterface объект
     * @expectedException TypeError
     */
    public function testSetItemsError()
    {
        $repository = new CategoriesRepository();
        $repository->items = new class () {};
    }
    
    /**
     * Тестирует метод CategoriesRepository::getGroup
     * вызываю с пустым CategoriesRepository::items
     * @expectedException yii\base\ErrorException
     */
    public function testGetGroupError()
    {
        $repository = new CategoriesRepository();
        $repository->getGroup();
    }
    
    /**
     * Тестирует метод CategoriesRepository::getGroup
     */
    public function testGetGroup()
    {
        $repository = new CategoriesRepository();
        $repository->items = new class () extends AbstractBaseComposit implements CategoriesCompositInterface {
            public $items = [];
            public function add(CategoriesModel $model) {
                $this->items[] = $model;
            }
            public function isEmpty() 
            {
                return empty($this->items) ? true : false;
            }
        };
        $result = $repository->getGroup();
        
        $this->assertTrue($result instanceof CategoriesCompositInterface);
        foreach ($result as $object) {
            $this->assertTrue($object instanceof CategoriesModel);
            $this->assertTrue(is_array($object->subcategory));
            $this->assertTrue($object->subcategory[0] instanceof SubcategoryModel);
        }
    }
    
    /**
     * Тестирует метод CategoriesRepository::getGroup
     * с применением критериев выборки
     */
    public function testGetGroupCriteria()
    {
        $repository = new CategoriesRepository();
        $repository->items = new class () extends AbstractBaseComposit implements CategoriesCompositInterface {
            public $items = [];
            public function add(CategoriesModel $model) {
                $this->items[] = $model;
            }
            public function isEmpty() 
            {
                return empty($this->items) ? true : false;
            }
        };
        $criteria = new QueryCriteria();
        $criteria->with('subcategory');
        $repository->setCriteria($criteria);
        $result = $repository->getGroup();
        
        $this->assertTrue($result instanceof CategoriesCompositInterface);
        foreach ($result as $object) {
            $this->assertTrue($object instanceof CategoriesModel);
            $this->assertTrue(is_array($object->subcategory));
            $this->assertTrue($object->subcategory[0] instanceof SubcategoryModel);
        }
    }
    
    /**
     * Тестирует метод CategoriesRepository::getGroup
     * при отсутствии данных, удовлетворяющих условиям SQL запроса
     */
    public function testGetGroupCriteriaNull()
    {
        $repository = new CategoriesRepository();
        $repository->items = new class () extends AbstractBaseComposit implements CategoriesCompositInterface {
            public $items = [];
            public function add(CategoriesModel $model) {
                $this->items[] = $model;
            }
            public function isEmpty() 
            {
                return empty($this->items) ? true : false;
            }
        };
        $criteria = new QueryCriteria();
        $criteria->where(['[[categories.id]]'=>[100,230]]);
        $repository->setCriteria($criteria);
        $result = $repository->getGroup();
        
        $this->assertNull($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}

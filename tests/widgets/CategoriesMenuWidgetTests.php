<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use app\widgets\CategoriesMenuWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\repositories\{AbstractBaseRepository,
    RepositoryInterface};
use app\models\{CategoriesModel,
    QueryCriteria};

class CategoriesMenuWidgetTests extends TestCase
{
    private static $dbClass;
    private $repository;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->repository = new class () extends AbstractBaseRepository implements RepositoryInterface {
            private $criteria;
            
            public function getGroup($request=null)
            {
                $query = CategoriesModel::find();
                $query = $this->addCriteria($query);
                $data = $query->all();
                return $data;
            }
            public function getOne($request=null)
            {
                
            }
            public function getCriteria()
            {
                $this->criteria = new QueryCriteria();
                return $this->criteria;
            }
        };
    }
    
    /**
     * Тестирует метод CategoriesMenuWidget::setRepository
     * вызываю с пустым $repository
     * @expectedException yii\base\ErrorException
     */
    public function testSetRepositoryEmpty()
    {
        $result = CategoriesMenuWidget::widget([]);
    }
    
    /**
     * Тестирует метод CategoriesMenuWidget::setRepository
     * передаю не поддерживающий RepositoryInterface объект
     * @expectedException TypeError
     */
    public function testSetRepositoryError()
    {
        $result = new CategoriesMenuWidget([
            'repository'=>new class() {},
        ]);
    }
    
    /**
     * Тестирует метод CategoriesMenuWidget::widget
     */
    public function testWidget()
    {
        $result = CategoriesMenuWidget::widget([
            'repository'=>$this->repository
        ]);
        
        print_r($result);
        
        $this->assertEquals(1, preg_match('/<ul class="categories-menu">/', $result));
        $this->assertEquals(1, preg_match('/<li><a href=".+">.+<\/a><\/li>/', $result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}

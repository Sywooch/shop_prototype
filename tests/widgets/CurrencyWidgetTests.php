<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\widgets\CurrencyWidget;
use app\repositories\{AbstractBaseRepository,
    RepositoryInterface};
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\models\{CurrencyModel,
    QueryCriteria};

/**
 * Тестирует класс app\widgets\CategoriesMenuWidget
 */
class CurrencyWidgetTests extends TestCase
{
    private static $dbClass;
    private $repository;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
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
                $query = CurrencyModel::find();
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
     * Тестирует метод CurrencyWidget::widget
     * вызываю с пустым $repository
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetRepositoryEmpty()
    {
        $result = CurrencyWidget::widget([]);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setRepository
     * передаю не поддерживающий RepositoryInterface объект
     * @expectedException TypeError
     */
    public function testSetRepositoryError()
    {
        $result = new CurrencyWidget([
            'repository'=>new class() {},
        ]);
    }
    
    /**
     * Тестирует метод CurrencyWidget::widget
     * вызываю с пустым $currency
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetCurrencyEmpty()
    {
        $result = CurrencyWidget::widget([
            'repository'=>$this->repository,
        ]);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setCurrency
     * передаю не наследующий Model объект
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $result = new CurrencyWidget([
            'repository'=>$this->repository,
            'currency'=>new class() {},
        ]);
    }
    
    /**
     * Тестирует метод CurrencyWidget::widget
     * вызываю с пустым $view
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetViewEmpty()
    {
        $result = CurrencyWidget::widget([
            'repository'=>$this->repository,
            'currency'=>new class() extends Model {},
        ]);
    }
    
    /**
     * Тестирует метод CurrencyWidget::widget
     */
    public function testWidget()
    {
        $result = CurrencyWidget::widget([
            'repository'=>$this->repository,
            'currency'=>new CurrencyModel(),
            'view'=>'currency-form.twig'
        ]);
        
        $this->assertEquals(1, preg_match('/<p><strong>'. \Yii::t('base', 'Currency:') . '<\/strong><\/p>/', $result));
        $this->assertEquals(1, preg_match('/<form id="set-currency-form"/', $result));
        $this->assertEquals(1, preg_match('/<select id="currencymodel-id"/', $result));
        $this->assertEquals(1, preg_match('/<input type="submit" value="'. \Yii::t('base', 'Change') . '">/', $result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}

<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\tests\DbManager;
use app\widgets\ProductDetailWidget;
use app\repositories\{AbstractBaseRepository,
    RepositoryInterface};
use app\tests\sources\fixtures\CategoriesFixture;

class ProductDetailWidgetTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setModel
     * вызываю с пустым $model
     * @expectedException yii\base\ErrorException
     */
    public function testSetModelEmpty()
    {
        $result = ProductDetailWidget::widget([]);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setModel
     * передаю не наследующий Model объект
     * @expectedException TypeError
     */
    public function testSetModelError()
    {
        $result = new ProductDetailWidget([
            'model'=>new class() {},
        ]);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::widget
     * вызываю с пустым $view
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetViewEmpty()
    {
        $result = new ProductDetailWidget([
            'model'=>new class() extends Model {},
        ]);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::widget
     * вызываю с пустым $price
     * @expectedException yii\base\ErrorException
     */
    /*public function testWidgetPriceEmpty()
    {
        $result = ProductDetailWidget::widget([
            'repository'=>$this->repository,
        ]);
    }*/
    
    /**
     * Тестирует метод ProductDetailWidget::widget
     */
    /*public function testWidget()
    {
        $result = ProductDetailWidget::widget([
            'repository'=>$this->repository,
            'price'=>178.25
        ]);
        
        $expected = \Yii::$app->formatter->asDecimal(178.25 * 12.34, 2) . ' UAH';
        $this->assertEquals($expected, $result);
    }*/
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}

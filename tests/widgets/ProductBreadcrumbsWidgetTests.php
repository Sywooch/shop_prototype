<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use app\widgets\ProductBreadcrumbsWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\ProductsModel;

class ProductBreadcrumbsWidgetTests extends TestCase
{
    private static $dbClass;
    private $repository;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод ProductBreadcrumbsWidget::setModel
     * вызываю с пустым $model
     * @expectedException yii\base\ErrorException
     */
    public function testSetModelEmpty()
    {
        $result = ProductBreadcrumbsWidget::widget([]);
    }
    
    /**
     * Тестирует метод ProductBreadcrumbsWidget::setRepository
     * передаю не наследующий Model объект
     * @expectedException TypeError
     */
    public function testSetRepositoryError()
    {
        $result = new ProductBreadcrumbsWidget([
            'model'=>new class() {},
        ]);
    }
    
    /**
     * Тестирует метод ProductBreadcrumbsWidget::widget
     */
    public function testWidget()
    {
        $result = ProductBreadcrumbsWidget::widget([
            'model'=>ProductsModel::find()->where(['[[id]]'=>1])->one()
        ]);
        
        print_r($result);
        
        $this->assertEquals(1, preg_match('/<ul class="breadcrumb">/', $result));
        $this->assertEquals(1, preg_match('/<li><a href=".+">.+<\/a><\/li>/', $result));
        $this->assertEquals(1, preg_match('/<li class="separator">.+<\/li>/', $result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}

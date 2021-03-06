<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\PopularGoodsWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;

/**
 * Тестирует класс PopularGoodsWidget
 */
class PopularGoodsWidgetTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PopularGoodsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PopularGoodsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('goods'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод PopularGoodsWidget::setGoods
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetGoodsError()
    {
        $goods = new class() {};
        
        $widget = new PopularGoodsWidget();
        $widget->setGoods($goods);
    }
    
    /**
     * Тестирует метод PopularGoodsWidget::setGoods
     */
    public function testSetGoods()
    {
        $goods = [new class() {}];
        
        $widget = new PopularGoodsWidget();
        $widget->setGoods($goods);
        
        $reflection = new \ReflectionProperty($widget, 'goods');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод PopularGoodsWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new PopularGoodsWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод PopularGoodsWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new PopularGoodsWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод PopularGoodsWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new PopularGoodsWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод PopularGoodsWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new PopularGoodsWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод PopularGoodsWidget::run
     * если пуст PopularGoodsWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $widget = new PopularGoodsWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод PopularGoodsWidget::run
     * если пуст PopularGoodsWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $widget = new PopularGoodsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод PopularGoodsWidget::run
     * если нет популярных товаров
     */
    public function testRunNotGoods()
    {
        $widget = new PopularGoodsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'popular-goods.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p>Нет популярных товаров</p>#', $result);
    }
    
    /**
     * Тестирует метод PopularGoodsWidget::run
     * если есть популярные товары
     */
    public function testRunExistProcessedPurchases()
    {
        $goods = [
            new class() {
                public $views = 564;
                public $seocode = 'good_1';
                public $name = 'Good 1';
                public $images = 'test';
            },
            new class() {
                public $views = 305;
                public $seocode = 'good_2';
                public $name = 'Good 2';
                public $images = 'test';
            },
        ];
        
        $widget = new PopularGoodsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'goods');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $goods);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'popular-goods.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#Просмотров: \d#', $result);
        $this->assertRegExp('#<a href=".+">Good \d</a>#', $result);
        $this->assertRegExp('#<img src=".+" height="200" alt="">#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}

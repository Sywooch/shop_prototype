<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\PaginationWidget;
use app\collections\{LightPagination,
    PaginationInterface};
use app\controllers\ProductsListController;

/**
 * Тестирует класс PaginationWidget
 */
class PaginationWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PaginationWidget::class);
        
        $this->assertTrue($reflection->hasProperty('pagination'));
        $this->assertTrue($reflection->hasProperty('activePage'));
        $this->assertTrue($reflection->hasProperty('childTag'));
        $this->assertTrue($reflection->hasProperty('separator'));
        $this->assertTrue($reflection->hasProperty('pageRange'));
        $this->assertTrue($reflection->hasProperty('edges'));
        $this->assertTrue($reflection->hasProperty('prevMin'));
        $this->assertTrue($reflection->hasProperty('nextMax'));
        $this->assertTrue($reflection->hasProperty('tags'));
        $this->assertTrue($reflection->hasProperty('pagePointer'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод PaginationWidget::setPagination
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPaginationError()
    {
        $pagination = new class() {};
        
        $widget = new PaginationWidget();
        $widget->setPagination($pagination);
    }
    
    /**
     * Тестирует метод PaginationWidget::setPagination
     */
    public function testSetPagination()
    {
        $pagination = new class() extends LightPagination {};
        
        $widget = new PaginationWidget();
        $widget->setPagination($pagination);
        
        $reflection = new \ReflectionProperty($widget, 'pagination');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(PaginationInterface::class, $result);
    }
    
    /**
     * Тестирует метод PaginationWidget::settings
     * при условии, что pageRange пуст
     */
    public function testSettingsEmptyPageRange()
    {
        $pagination = new class() extends LightPagination {
            private $page;
            public function getPage(){
                return $this->page;
            }
        };
        
        $widget = new PaginationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'pageRange');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, null);
        
        $reflection = new \ReflectionProperty($widget, 'pagination');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $pagination);
        
        $reflection = new \ReflectionMethod($widget, 'settings');
        $reflection->setAccessible(true);
        $reflection->invoke($widget);
        
        $reflection = new \ReflectionProperty($widget, 'pageRange');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertSame(3, $result);
    }
    
    /**
     * Тестирует метод PaginationWidget::settings
     * при условии, что pageRange четное
     */
    public function testSettingsEvenPageRange()
    {
        $pagination = new class() extends LightPagination {
            private $page;
            public function getPage(){
                return $this->page;
            }
        };
        
        $widget = new PaginationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'pageRange');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 4);
        
        $reflection = new \ReflectionProperty($widget, 'pagination');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $pagination);
        
        $reflection = new \ReflectionMethod($widget, 'settings');
        $reflection->setAccessible(true);
        $reflection->invoke($widget);
        
        $reflection = new \ReflectionProperty($widget, 'pageRange');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertSame(5, $result);
    }
    
    /**
     * Тестирует метод PaginationWidget::settings
     * pagePointer = pagination->page + 1 
     */
    public function testSettingsPagePointer()
    {
        $pagination = new class() extends LightPagination {
            private $page = 14;
            public function getPage(){
                return $this->page;
            }
        };
        
        $widget = new PaginationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'pagination');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $pagination);
        
        $reflection = new \ReflectionMethod($widget, 'settings');
        $reflection->setAccessible(true);
        $reflection->invoke($widget);
        
        $reflection = new \ReflectionProperty($widget, 'pagePointer');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertSame(15, $result);
    }
    
    /**
     * Тестирует метод PaginationWidget::getRange
     */
    public function testGetRange()
    {
        $pagination = new class() extends LightPagination {
            public function getPageCount(){
                return 15;
            }
            public function getPage(){
                return $this->page;
            }
        };
        
        $widget = new PaginationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'pagination');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $pagination);
        
        $reflection = new \ReflectionProperty($widget, 'pageRange');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 20);
        
        $reflection = new \ReflectionMethod($widget, 'getRange');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($widget);
        
        $this->assertCount(15, $result);
        $this->assertSame(1, $result[0]);
        $this->assertSame(15, array_pop($result));
    }
    
    /**
     * Тестирует метод PaginationWidget::scale
     *  увеличение максимального номера доступных страниц
     */
    public function testScaleUp()
    {
        $widget = new PaginationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'pageRange');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 31);
        
        $reflection = new \ReflectionProperty($widget, 'prevMin');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 1);
        
        $reflection = new \ReflectionProperty($widget, 'nextMax');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 18);
        
        $reflection = new \ReflectionMethod($widget, 'scale');
        $reflection->setAccessible(true);
        $reflection->invoke($widget, true);
        
        $reflection = new \ReflectionProperty($widget, 'nextMax');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertSame(31, $result);
    }
    
    /**
     * Тестирует метод PaginationWidget::scale
     *  уменьшает минимальный номер доступных страниц
     */
    public function testScaleDown()
    {
        $widget = new PaginationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'pageRange');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 31);
        
        $reflection = new \ReflectionProperty($widget, 'prevMin');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 25);
        
        $reflection = new \ReflectionProperty($widget, 'nextMax');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 31);
        
        $reflection = new \ReflectionMethod($widget, 'scale');
        $reflection->setAccessible(true);
        $reflection->invoke($widget, false);
        
        $reflection = new \ReflectionProperty($widget, 'prevMin');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertSame(1, $result);
    }
    
     /**
     * Тестирует метод PaginationWidget::checkMinPage
     */
    public function testCheckMinPage()
    {
        $widget = new PaginationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'prevMin');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, -2);
        
        $reflection = new \ReflectionMethod($widget, 'checkMinPage');
        $reflection->setAccessible(true);
        $reflection->invoke($widget);
        
        $reflection = new \ReflectionProperty($widget, 'prevMin');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertSame(1, $result);
    }
    
    /**
     * Тестирует метод PaginationWidget::checkMaxPage
     */
    public function testCheckMaxPage()
    {
        $pagination = new class() extends LightPagination {
            public function getPageCount(){
                return 15;
            }
            public function getPage(){
                return $this->page;
            }
        };
        
        $widget = new PaginationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'pagination');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $pagination);
        
        $reflection = new \ReflectionProperty($widget, 'nextMax');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 50);
        
        $reflection = new \ReflectionMethod($widget, 'checkMaxPage');
        $reflection->setAccessible(true);
        $reflection->invoke($widget);
        
        $reflection = new \ReflectionProperty($widget, 'nextMax');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertSame(15, $result);
    }
    
    /**
     * Тестирует метод PaginationWidget::run
     * если пуст PaginationWidget::pagination
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: pagination
     */
    public function testRunEmptyPagination()
    {
        $widget = new PaginationWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод PaginationWidget::run
     * если пуст PaginationWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $pagination = new class() extends LightPagination {};
        
        $widget = new PaginationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'pagination');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $pagination);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод PaginationWidget::run
     */
    public function testRun()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $pagination = new class() extends LightPagination {
            private $page = 3;
            public function getPageCount(){
                return 15;
            }
            public function getPage(){
                return $this->page;
            }
        };
        
        $widget = new PaginationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'pagination');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $pagination);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setValue($widget, 'pagination.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<ul class="pagination">#', $result);
        $this->assertRegExp('#<il><a href=".+">Первая</a></il>#', $result);
        $this->assertRegExp('#<il><a href=".+\?page=2">2</a></il>#', $result);
        $this->assertRegExp('#<il><a href=".+\?page=3">3</a></il>#', $result);
        $this->assertRegExp('#<il class="active">4</il>#', $result);
        $this->assertRegExp('#<il><a href=".+\?page=5">5</a></il>#', $result);
        $this->assertRegExp('#<il><a href=".+\?page=6">6</a></il>#', $result);
        $this->assertRegExp('#<il><a href=".+\?page=15">Последняя</a></il>#', $result);
    }
}

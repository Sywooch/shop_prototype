<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\PaginationWidget;
use app\collections\PaginationInterface;
use yii\db\Query;
use yii\base\Object;

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
        $pagination = new class() implements PaginationInterface {
            public function setPageSize(int $size){}
            public function setPage(int $number){}
            public function setTotalCount(Query $query){}
            public function getPageCount(){}
            public function getOffset(){}
            public function getLimit(){}
            public function getPage(){}
        };
        
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
        $pagination = new class() extends Object implements PaginationInterface {
            private $page;
            public function setPageSize(int $size){}
            public function setPage(int $number){}
            public function setTotalCount(Query $query){}
            public function getPageCount(){}
            public function getOffset(){}
            public function getLimit(){}
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
        $pagination = new class() extends Object implements PaginationInterface {
            private $page;
            public function setPageSize(int $size){}
            public function setPage(int $number){}
            public function setTotalCount(Query $query){}
            public function getPageCount(){}
            public function getOffset(){}
            public function getLimit(){}
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
        $pagination = new class() extends Object implements PaginationInterface {
            private $page = 14;
            public function setPageSize(int $size){}
            public function setPage(int $number){}
            public function setTotalCount(Query $query){}
            public function getPageCount(){}
            public function getOffset(){}
            public function getLimit(){}
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
        $pagination = new class() extends Object implements PaginationInterface {
            public function setPageSize(int $size){}
            public function setPage(int $number){}
            public function setTotalCount(Query $query){}
            public function getPageCount(){
                return 15;
            }
            public function getOffset(){}
            public function getLimit(){}
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
        $pagination = new class() extends Object implements PaginationInterface {
            public function setPageSize(int $size){}
            public function setPage(int $number){}
            public function setTotalCount(Query $query){}
            public function getPageCount(){
                return 15;
            }
            public function getOffset(){}
            public function getLimit(){}
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
}

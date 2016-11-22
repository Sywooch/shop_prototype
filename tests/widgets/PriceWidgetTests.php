<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use app\widgets\PriceWidget;
use app\repositories\{AbstractBaseRepository,
    RepositoryInterface};

class PriceWidgetTests extends TestCase
{
    private $repository;
    
    public function setUp()
    {
        $this->repository = new class () extends AbstractBaseRepository implements RepositoryInterface {
            private $criteria;
            
            public function getGroup($request=null)
            {
            }
            public function getOne($request=null)
            {
                return new class() {
                    public $exchange_rate = 12.34;
                    public $code = 'UAH';
                };
            }
            public function getCriteria()
            {
            }
        };
    }
    
    /**
     * Тестирует метод PriceWidget::setRepository
     * вызываю с пустым $repository
     * @expectedException yii\base\ErrorException
     */
    public function testSetRepositoryEmpty()
    {
        $result = PriceWidget::widget([]);
    }
    
    /**
     * Тестирует метод PriceWidget::setRepository
     * передаю не поддерживающий RepositoryInterface объект
     * @expectedException TypeError
     */
    public function testSetRepositoryError()
    {
        $result = new PriceWidget([
            'repository'=>new class() {},
        ]);
    }
    
    /**
     * Тестирует метод PriceWidget::widget
     * вызываю с пустым $price
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetPriceEmpty()
    {
        $result = PriceWidget::widget([
            'repository'=>$this->repository,
        ]);
    }
    
    /**
     * Тестирует метод PriceWidget::widget
     */
    public function testWidget()
    {
        $result = PriceWidget::widget([
            'repository'=>$this->repository,
            'price'=>178.25
        ]);
        
        $expected = \Yii::$app->formatter->asDecimal(178.25 * 12.34, 2) . ' UAH';
        $this->assertEquals($expected, $result);
    }
}

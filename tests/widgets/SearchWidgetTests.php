<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\SearchWidget;

class SearchWidgetTests extends TestCase
{
    /**
     * Тестирует метод SearchWidget::widget()
     * вызываю с пустым SearchWidget::view
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetErrorView()
    {
        $result = SearchWidget::widget([]);
    }
    
    /**
     * Тестирует метод SearchWidget::widget()
     */
    public function testWidget()
    {
        $_GET = [\Yii::$app->params['searchKey']=>'Some search text'];
        
        $result = SearchWidget::widget([
            'view'=>'search.twig'
        ]);
        
        $this->assertEquals(1, preg_match('/<div class="search-form">/', $result));
        $this->assertEquals(1, preg_match('/<form id="search-form" name="search-form"/', $result));
        $this->assertEquals(1, preg_match('/value="Some search text"/', $result));
        $this->assertEquals(1, preg_match('/<input type="submit" value="' . \Yii::t('base', 'Search') . '">/', $result));
    }
    
    /**
     * Тестирует метод SearchWidget::widget()
     * поисковый запрос пуст
     */
    public function testWidgetEmpty()
    {
        $_GET = [];
        
        $result = SearchWidget::widget([
            'view'=>'search.twig'
        ]);
        
        $this->assertEquals(1, preg_match('/<div class="search-form">/', $result));
        $this->assertEquals(1, preg_match('/<form id="search-form" name="search-form"/', $result));
        $this->assertEquals(1, preg_match('/value=""/', $result));
        $this->assertEquals(1, preg_match('/<input type="submit" value="' . \Yii::t('base', 'Search') . '">/', $result));
    }
}

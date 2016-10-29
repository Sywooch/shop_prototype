<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ImagesWidget;

/**
 * Тестирует класс app\widgets\ImagesWidget
 */
class ImagesWidgetTests extends TestCase
{
    private static $_path = 'test';
    
    public function testWidget()
    {
        $result = ImagesWidget::widget(['path'=>self::$_path]);
        
        $imagesArray = glob(\Yii::getAlias('@imagesroot/' . self::$_path) . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        
        $imagesCount = 0;
        
        foreach ($imagesArray as $image) {
            $position = strpos(basename($image), 'thumbn_');
            if ($position === false || $position > 0) {
                $this->assertRegExp('/' . basename($image) . '/', $result);
                ++$imagesCount;
            }
        }
        
        preg_match_all('/(<img src=".+?">)/', $result, $matches, PREG_PATTERN_ORDER);
        
        $this->assertEquals($imagesCount, count($matches[1]));
    }
}

<?php

namespace app\tests\helpers;

use app\tests\MockObject;
use app\helpers\PicturesHelper;

/**
 * Тестирует класс app\helpers\PicturesHelper;
 */
class PicturesHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_imgPath = '/var/www/html/shop/tests/source/images/1919842.jpg';
    private static $_testPath = '/var/www/html/shop/tests/source/images/test_1919842.jpg';
    
    public static function setUpBeforeClass()
    {
        $imagick = new \Imagick(self::$_imgPath);
        $imagick->writeImage(self::$_testPath);
    }
    
    /**
     * Тестирует метод PicturesHelper::createPictures
     */
    public function testCreatePictures()
    {
        $imagick = new \Imagick(self::$_testPath);
        
        $this->assertTrue($imagick->getImageWidth() > \Yii::$app->params['maxWidth']);
        $this->assertTrue($imagick->getImageHeight() > \Yii::$app->params['maxHeight']);
        
        $object = new MockObject();
        $object->tempName = self::$_testPath;
        
        $result = PicturesHelper::createPictures([$object]);
        
        $this->assertTrue($result);
        
        $imagick = new \Imagick(self::$_testPath);
        
        $this->assertTrue($imagick->getImageWidth() <= \Yii::$app->params['maxWidth']);
        $this->assertTrue($imagick->getImageHeight() <= \Yii::$app->params['maxHeight']);
    }
    
    public static function tearDownAfterClass()
    {
        if (file_exists(self::$_testPath)) {
            unlink(self::$_testPath);
        }
    }
}

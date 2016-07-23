<?php

namespace app\tests\helpers;

use app\tests\MockObject;
use app\helpers\PicturesHelper;

/**
 * Тестирует класс app\helpers\PicturesHelper;
 */
class PicturesHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_sourceCatalog = '/var/www/html/shop/tests/source/images';
    private static $_testCatalog = '/var/www/html/shop/tests/source/images/test';
    private static $_fileName = '1919842.jpg';
    
    public static function setUpBeforeClass()
    {
        if (!file_exists(self::$_testCatalog) || !is_dir(self::$_testCatalog)) {
            mkdir(self::$_testCatalog, 0775);
        }
        
        $imagick = new \Imagick(self::$_sourceCatalog . '/' . self::$_fileName);
        $imagick->writeImage(self::$_testCatalog . '/' . self::$_fileName);
    }
    
    /**
     * Тестирует метод PicturesHelper::createPictures
     */
    public function testCreatePictures()
    {
        $imagick = new \Imagick(self::$_testCatalog . '/' . self::$_fileName);
        
        $this->assertTrue($imagick->getImageWidth() > \Yii::$app->params['maxWidth']);
        $this->assertTrue($imagick->getImageHeight() > \Yii::$app->params['maxHeight']);
        
        $object = new MockObject();
        $object->tempName = self::$_testCatalog . '/' . self::$_fileName;
        
        $result = PicturesHelper::createPictures([$object]);
        
        $this->assertTrue($result);
        
        $imagick = new \Imagick(self::$_testCatalog . '/' . self::$_fileName);
        
        $this->assertTrue($imagick->getImageWidth() <= \Yii::$app->params['maxWidth']);
        $this->assertTrue($imagick->getImageHeight() <= \Yii::$app->params['maxHeight']);
    }
    
    /**
     * Тестирует метод PicturesHelper::createThumbnails
     */
    public function testCreateThumbnails()
    {
        $result = PicturesHelper::createThumbnails(self::$_testCatalog);
        
        $this->assertTrue($result);
        $this->assertTrue(file_exists(self::$_testCatalog . '/' . \Yii::$app->params['thumbnailsPrefix'] . self::$_fileName));
        
        $imagick = new \Imagick(self::$_testCatalog . '/' . \Yii::$app->params['thumbnailsPrefix'] . self::$_fileName);
        
        $this->assertTrue($imagick->getImageWidth() <= \Yii::$app->params['maxThumbnailWidth']);
        $this->assertTrue($imagick->getImageHeight() <= \Yii::$app->params['maxThumbnailHeight']);
    }
    
    public static function tearDownAfterClass()
    {
        if (file_exists(self::$_testCatalog . '/' . self::$_fileName)) {
            unlink(self::$_testCatalog . '/' . self::$_fileName);
        }
        
        if (file_exists(self::$_testCatalog . '/' . \Yii::$app->params['thumbnailsPrefix'] . self::$_fileName)) {
            unlink(self::$_testCatalog . '/' . \Yii::$app->params['thumbnailsPrefix'] . self::$_fileName);
        }
        
        if (file_exists(self::$_testCatalog) && is_dir(self::$_testCatalog)) {
            rmdir(self::$_testCatalog);
        }
    }
}

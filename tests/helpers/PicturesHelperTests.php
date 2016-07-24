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
    private static $_testCatalog = 'test';
    private static $_fileName = '1919842.jpg';
    
    public static function setUpBeforeClass()
    {
        if (!file_exists(self::$_sourceCatalog . '/' . self::$_testCatalog) || !is_dir(self::$_sourceCatalog . '/' . self::$_testCatalog)) {
            mkdir(self::$_sourceCatalog . '/' . self::$_testCatalog, 0775);
        }
        
        $imagick = new \Imagick(self::$_sourceCatalog . '/' . self::$_fileName);
        $imagick->writeImage(self::$_sourceCatalog . '/' . self::$_testCatalog . '/' . self::$_fileName);
    }
    
    /**
     * Тестирует метод PicturesHelper::createPictures
     */
    public function testCreatePictures()
    {
        $imagick = new \Imagick(self::$_sourceCatalog . '/' . self::$_testCatalog . '/' . self::$_fileName);
        
        $this->assertTrue($imagick->getImageWidth() > \Yii::$app->params['maxWidth']);
        $this->assertTrue($imagick->getImageHeight() > \Yii::$app->params['maxHeight']);
        
        $object = new MockObject();
        $object->tempName = self::$_sourceCatalog . '/' . self::$_testCatalog . '/' . self::$_fileName;
        
        $result = PicturesHelper::createPictures([$object]);
        
        $this->assertTrue($result);
        
        $imagick = new \Imagick(self::$_sourceCatalog . '/' . self::$_testCatalog . '/' . self::$_fileName);
        
        $this->assertTrue($imagick->getImageWidth() <= \Yii::$app->params['maxWidth']);
        $this->assertTrue($imagick->getImageHeight() <= \Yii::$app->params['maxHeight']);
    }
    
    /**
     * Тестирует метод PicturesHelper::createThumbnails
     */
    public function testCreateThumbnails()
    {
        $result = PicturesHelper::createThumbnails(self::$_sourceCatalog . '/' . self::$_testCatalog);
        
        $this->assertTrue($result);
        $this->assertTrue(file_exists(self::$_sourceCatalog . '/' . self::$_testCatalog . '/' . \Yii::$app->params['thumbnailsPrefix'] . self::$_fileName));
        
        $imagick = new \Imagick(self::$_sourceCatalog . '/' . self::$_testCatalog . '/' . \Yii::$app->params['thumbnailsPrefix'] . self::$_fileName);
        
        $this->assertTrue($imagick->getImageWidth() <= \Yii::$app->params['maxThumbnailWidth']);
        $this->assertTrue($imagick->getImageHeight() <= \Yii::$app->params['maxThumbnailHeight']);
    }
    
    /**
     * Тестирует метод PicturesHelper::getPathImages
     */
    public function testGetPathImages()
    {
        $result = PicturesHelper::getPathImages(self::$_sourceCatalog, self::$_testCatalog);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(2, count($result));
        $this->assertTrue(array_key_exists(\Yii::$app->params['thumbnails'], $result));
        $this->assertTrue(array_key_exists(\Yii::$app->params['fullpath'], $result));
        
        $this->assertTrue(is_array($result[\Yii::$app->params['thumbnails']]));
        $this->assertFalse(empty($result[\Yii::$app->params['thumbnails']]));
        $this->assertTrue(is_array($result[\Yii::$app->params['fullpath']]));
        $this->assertFalse(empty($result[\Yii::$app->params['fullpath']]));
        
        $expect = \Yii::getAlias('@wpic/' . self::$_testCatalog . '/' . \Yii::$app->params['thumbnailsPrefix'] . self::$_fileName);
        $actual = $result[\Yii::$app->params['thumbnails']][0];
        $this->assertEquals($expect, $actual);
        
        $expect = \Yii::getAlias('@wpic/' . self::$_testCatalog . '/' . self::$_fileName);
        $actual = $result[\Yii::$app->params['fullpath']][0];
        $this->assertEquals($expect, $actual);
    }
    
    /**
     * Тестирует метод PicturesHelper::getOneThumbnail
     */
    public function testGetOneThumbnail()
    {
        $result = PicturesHelper::getOneThumbnail(self::$_sourceCatalog, self::$_testCatalog);
        
        $this->assertTrue(is_string($result));
        $this->assertTrue((bool)strpos($result, \Yii::$app->params['thumbnailsPrefix']));
    }
    
    public static function tearDownAfterClass()
    {
        if (file_exists(self::$_sourceCatalog . '/' . self::$_testCatalog . '/' . self::$_fileName)) {
            unlink(self::$_sourceCatalog . '/' . self::$_testCatalog . '/' . self::$_fileName);
        }
        
        if (file_exists(self::$_sourceCatalog . '/' . self::$_testCatalog . '/' . \Yii::$app->params['thumbnailsPrefix'] . self::$_fileName)) {
            unlink(self::$_sourceCatalog . '/' . self::$_testCatalog . '/' . \Yii::$app->params['thumbnailsPrefix'] . self::$_fileName);
        }
        
        if (file_exists(self::$_sourceCatalog . '/' . self::$_testCatalog) && is_dir(self::$_sourceCatalog . '/' . self::$_testCatalog)) {
            rmdir(self::$_sourceCatalog . '/' . self::$_testCatalog);
        }
    }
}

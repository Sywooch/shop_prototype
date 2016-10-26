<?php

namespace app\tests\helpers;

use PHPUnit\Framework\TestCase;
use yii\web\UploadedFile;
use app\helpers\PicturesHelper;

/**
 * Тестирует класс app\helpers\PicturesHelper
 */
class PicturesHelperTests extends TestCase
{
    private static $_tempPath;
    
    public static function setUpBeforeClass()
    {
        self::$_tempPath = \Yii::getAlias('@imagestemp');
        if (!file_exists(self::$_tempPath)) {
            mkdir(self::$_tempPath);
        }
        $imagesArray = glob(dirname(self::$_tempPath) . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        copy($imagesArray[0], self::$_tempPath . '/' . basename($imagesArray[0]));
    }
    
    /**
     * Тестирует метод PicturesHelper::resize
     */
    public function testResize()
    {
        $imagePath = glob(self::$_tempPath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE)[0];
        
        $this->assertFalse(empty($imagePath));
        
        $image = new \Imagick($imagePath);
        $this->assertTrue($image->getImageWidth() > \Yii::$app->params['maxWidth'] || $image->getImageHeight() > \Yii::$app->params['maxHeight']);
        
        PicturesHelper::resize(new class(['tempName'=>$imagePath]) extends UploadedFile {
            public $tempName;
        });
        
        $image = new \Imagick($imagePath);
        $this->assertTrue($image->getImageWidth() <= \Yii::$app->params['maxWidth'] && $image->getImageHeight() <= \Yii::$app->params['maxHeight']);
    }
    
    /**
     * Тестирует метод PicturesHelper::createThumbnails
     */
    public function testCreateThumbnails()
    {
        PicturesHelper::createThumbnails(self::$_tempPath);
        
        $imagePath = glob(self::$_tempPath . '/thumbn_*.{jpg,jpeg,png,gif}', GLOB_BRACE)[0];
        
        $this->assertFalse(empty($imagePath));
        
        $image = new \Imagick($imagePath);
        $this->assertTrue($image->getImageWidth() <= \Yii::$app->params['maxThumbnailWidth'] && $image->getImageHeight() <= \Yii::$app->params['maxThumbnailHeight']);
    }
    
    /**
     * Тестирует метод PicturesHelper::remove
     */
    public function testRemove()
    {
       $this->assertTrue(file_exists(self::$_tempPath));
       $this->assertTrue(is_dir(self::$_tempPath));
       
       PicturesHelper::remove(self::$_tempPath);
       
       $this->assertFalse(file_exists(self::$_tempPath));
    }
    
    public static function tearDownAfterClass()
    {
        if (file_exists(self::$_tempPath)) {
            $imagesArray = glob(self::$_tempPath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            foreach ($imagesArray as $image) {
                unlink($image);
            }
            rmdir(self::$_tempPath);
        }
    }
}



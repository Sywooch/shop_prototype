<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\helpers\ImgHelper;
use yii\web\UploadedFile;
use app\forms\AdminProductForm;

/**
 * Тестирует класс ImgHelper
 */
class ImgHelperTests extends TestCase
{
    public static function setUpBeforeClass()
    {
        $path = \Yii::getAlias('@imagesroot/other');
        
        mkdir($path);
        
        $filesArray = glob(\Yii::getAlias('@imagesroot/test') . '/*.{jpg,png,gif}', GLOB_BRACE);
        
        foreach ($filesArray as $file) {
            if (preg_match('#thumbn_#', $file) === 0) {
                $imagick = new \Imagick($file);
                $newFile = fopen($path . '/' . basename($file), 'w');
                $imagick->writeImageFile($newFile);
                fclose($newFile);
            }
        }
    }
    
    /**
     * Тестирует метод ImgHelper::randThumbn
     */
    public function testRandThumbn()
    {
        $result = ImgHelper::randThumbn('test');
        
        $this->assertRegExp('#<img src=".+" height="200" alt="">#', $result);
    }
    
    /**
     * Тестирует метод ImgHelper::allThumbn
     */
    public function testAllThumbn()
    {
        $result = ImgHelper::allThumbn('test');
        
        $this->assertRegExp('#<img src=".+" height="50" alt=""><img src=".+" height="50" alt="">#', $result);
    }
    
    /**
     * Тестирует метод ImgHelper::saveImages
     */
    public function testSaveImages()
    {
        $reflection = new \ReflectionProperty(UploadedFile::class, '_files');
        $reflection->setAccessible(true);
        $reflection->setValue(null);
        
        $filesArray = [
            'MockForm' => [
                'name' => [
                    'images'=>[
                        0=>'1.jpg', 
                        1=>'3.jpg'
                    ]
                ],
                'type' => [
                    'images'=>[
                        0=>'image/jpeg', 
                        1=>'image/jpeg'
                    ]
                ],
                'tmp_name' => [
                    'images'=>[
                        0=>'/var/www/html/shop/tests/sources/images/m1.jpg', 
                        1=>'/var/www/html/shop/tests/sources/images/m2.jpg'
                    ]
                ],
                'size' => [
                    'images' => [
                        0=>11037,
                        1=>1024*300
                    ]
                ],
                'error' => [
                    'images' => [
                        0=>0,
                        1=>0,
                    ]
                ],
            ],
        ];
        
        $_FILES = $filesArray;
        $uploadedFiles = UploadedFile::getInstancesByName('MockForm[images]');
        
        $result = ImgHelper::saveImages($uploadedFiles);
        
        $this->assertInternalType('integer', $result);
        $this->assertEquals(10, mb_strlen($result, 'UTF-8'));
        
        $path = \Yii::getAlias('@imagesroot' . '/' . $result);
        $this->assertTrue(file_exists($path));
        $this->assertTrue(is_dir($path));
        $files = glob($path . '/*.{jpg,gif,png}', GLOB_BRACE);
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод ImgHelper::makeThumbn
     */
    public function testMakeThumbn()
    {
        $filesArray = glob(\Yii::getAlias('@imagesroot/other') . '/thumbn_*.{jpg,png,gif}', GLOB_BRACE);
        $this->assertEmpty($filesArray);
        
        ImgHelper::makeThumbn('other');
        
        $filesArray = glob(\Yii::getAlias('@imagesroot/other') . '/thumbn_*.{jpg,png,gif}', GLOB_BRACE);
        $this->assertNotEmpty($filesArray);
    }
    
    /**
     * Тестирует метод ImgHelper::removeImages
     * @depends testMakeThumbn
     */
    public function testRemoveImages()
    {
        $filesArray = glob(\Yii::getAlias('@imagesroot/other') . '/thumbn_*.{jpg,png,gif}', GLOB_BRACE);
        $this->assertNotEmpty($filesArray);
        
        ImgHelper::removeImages('other');
        
        $filesArray = glob(\Yii::getAlias('@imagesroot/other') . '/thumbn_*.{jpg,png,gif}', GLOB_BRACE);
        $this->assertEmpty($filesArray);
        
        $this->assertFalse(file_exists(\Yii::getAlias('@imagesroot/other')));
    }
    
    public static function tearDownAfterClass()
    {
        $dirsArray = glob(\Yii::getAlias('@imagesroot') . '/*');
        foreach ($dirsArray as $dir) {
            if (preg_match('#test$#', $dir) === 0) {
                $files = glob($dir . '/*.{jpg,gif,png}', GLOB_BRACE);
                if (!empty($files)) {
                    foreach ($files as $file) {
                        unlink($file);
                    }
                }
                rmdir($dir);
            }
        }
    }
}

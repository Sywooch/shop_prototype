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
}

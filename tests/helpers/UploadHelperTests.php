<?php

namespace app\tests\helpers;

use app\helpers\UploadHelper;
use app\tests\MockObject;

/**
 * Тестирует класс app\helpers\UploadHelper
 */
class UploadHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_catalogName;
    
    /**
     * Тестирует метод UploadHelper::saveImages
     */
    public function testSaveImages()
    {
        $result = UploadHelper::saveImages([new MockObject()]);
        
        $this->assertTrue($result);
    }
    
    /**
     * Тестирует метод UploadHelper::getСatalogName
     */
    public function testGetСatalogName()
    {
        self::$_catalogName = UploadHelper::getСatalogName();
        
        $this->assertFalse(empty(self::$_catalogName));
        $this->assertTrue(file_exists(\Yii::getAlias('@pic/' . self::$_catalogName)));
        $this->assertTrue(is_dir(\Yii::getAlias('@pic/' . self::$_catalogName)));
    }
    
    public static function tearDownAfterClass()
    {
        $fullPath = \Yii::getAlias('@pic/' . self::$_catalogName);
        if (!empty($fullPath)) {
            if (file_exists($fullPath)) {
                rmdir($fullPath);
            }
        }
    }
}

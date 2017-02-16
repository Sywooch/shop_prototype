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
    /*public function testSaveImages()
    {
        $filesArray = [
            'AdminProductForm' => [
                'name' => [
                    'images'=>[
                        0=>'m1.jpg', 
                        1=>'m2.jpg'
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
                        1=>(1024*1024)*2
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
        
        $form = new AdminProductForm();
        
        $_FILES = $filesArray;
        
        $result = ImgHelper::saveImages($form, 'images');
    }*/
}

<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\UploadedFile;
use app\handlers\AdminAddProductPostRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ProductsFixture,
    ProductsColorsFixture,
    ProductsSizesFixture};
use app\forms\AdminProductForm;

/**
 * Тестирует класс AdminAddProductPostRequestHandler
 */
class AdminAddProductPostRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'products_size'=>ProductsSizesFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminAddProductPostRequestHandler();
    }
    
    /**
     * Тестирует метод AdminAddProductPostRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminProductForm'=>[
                        'code'=>null,
                        'name'=>'New Name',
                        'short_description'=>'New short escription',
                        'description'=>'New description',
                        'price'=>46897.88,
                        'id_category'=>2,
                        'id_subcategory'=>2,
                        'id_colors'=>[1, 2, 3],
                        'id_sizes'=>[1, 2],
                        'id_brand'=>2,
                        'active'=>true,
                        'total_products'=>204,
                        'seocode'=>'new-product',
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminAddProductPostRequestHandler::handle
     */
    public function testHandle()
    {
        $reflection = new \ReflectionProperty(UploadedFile::class, '_files');
        $reflection->setAccessible(true);
        $reflection->setValue(null);
        
        $filesArray = [
            'AdminProductForm' => [
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
        
        $product = \Yii::$app->db->createCommand('SELECT * FROM {{products}} WHERE [[code]]=:code')->bindValue(':code', 'NEWCODE')->queryOne();
        $this->assertEmpty($product);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminProductForm'=>[
                        'code'=>'NEWCODE',
                        'name'=>'New Name',
                        'short_description'=>'New short escription',
                        'description'=>'New description',
                        'price'=>46897.88,
                        'id_category'=>2,
                        'id_subcategory'=>1,
                        'id_colors'=>[1, 2],
                        'id_sizes'=>[1, 2],
                        'id_brand'=>2,
                        'active'=>true,
                        'total_products'=>204,
                        'seocode'=>'new-product',
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('successText', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertInternalType('string', $result['successText']);
        $this->assertInternalType('string', $result['form']);
        
        $product = \Yii::$app->db->createCommand('SELECT * FROM {{products}} WHERE [[code]]=:code')->bindValue(':code', 'NEWCODE')->queryOne();
        $this->assertNotEmpty($product);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
        
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

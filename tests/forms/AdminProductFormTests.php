<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\AdminProductForm;
use yii\web\UploadedFile;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;

/**
 * Тестирует класс AdminProductForm
 */
class AdminProductFormTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AdminProductForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductForm::class);
        
        $this->assertTrue($reflection->hasConstant('CREATE'));
        $this->assertTrue($reflection->hasConstant('EDIT'));
        $this->assertTrue($reflection->hasConstant('GET'));
        $this->assertTrue($reflection->hasConstant('DELETE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('code'));
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('short_description'));
        $this->assertTrue($reflection->hasProperty('description'));
        $this->assertTrue($reflection->hasProperty('price'));
        $this->assertTrue($reflection->hasProperty('images'));
        $this->assertTrue($reflection->hasProperty('id_category'));
        $this->assertTrue($reflection->hasProperty('id_subcategory'));
        $this->assertTrue($reflection->hasProperty('id_colors'));
        $this->assertTrue($reflection->hasProperty('id_sizes'));
        $this->assertTrue($reflection->hasProperty('id_brand'));
        $this->assertTrue($reflection->hasProperty('active'));
        $this->assertTrue($reflection->hasProperty('total_products'));
        $this->assertTrue($reflection->hasProperty('seocode'));
        $this->assertTrue($reflection->hasProperty('views'));
        $this->assertTrue($reflection->hasProperty('related'));
    }
    
    /**
     * Тестирует метод AdminProductForm::scenarios
     */
    public function testScenarios()
    {
        $form = new AdminProductForm(['scenario'=>AdminProductForm::CREATE]);
        $form->attributes = [
            'code'=>'HJU780-R',
            'name'=>'Product 1',
            'short_description'=>'Short description 1',
            'description'=>'Description 1',
            'price'=>568.89,
            'images'=>'test',
            'id_category'=>1,
            'id_subcategory'=>2,
            'id_colors'=>[1, 2, 3],
            'id_sizes'=>[2, 4],
            'id_brand'=>1,
            'active'=>true,
            'total_products'=>568,
            'seocode'=>'product',
            'related'=>'1,2,3'
        ];
        
        $reflection = new \ReflectionProperty($form, 'code');
        $result = $reflection->getValue($form);
        $this->assertSame('HJU780-R', $result);
        $reflection = new \ReflectionProperty($form, 'name');
        $result = $reflection->getValue($form);
        $this->assertSame('Product 1', $result);
        $reflection = new \ReflectionProperty($form, 'short_description');
        $result = $reflection->getValue($form);
        $this->assertSame('Short description 1', $result);
        $reflection = new \ReflectionProperty($form, 'description');
        $result = $reflection->getValue($form);
        $this->assertSame('Description 1', $result);
        $reflection = new \ReflectionProperty($form, 'price');
        $result = $reflection->getValue($form);
        $this->assertSame(568.89, $result);
        $reflection = new \ReflectionProperty($form, 'images');
        $result = $reflection->getValue($form);
        $this->assertSame('test', $result);
        $reflection = new \ReflectionProperty($form, 'id_category');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
        $reflection = new \ReflectionProperty($form, 'id_subcategory');
        $result = $reflection->getValue($form);
        $this->assertSame(2, $result);
        $reflection = new \ReflectionProperty($form, 'id_colors');
        $result = $reflection->getValue($form);
        $this->assertSame([1, 2, 3], $result);
        $reflection = new \ReflectionProperty($form, 'id_sizes');
        $result = $reflection->getValue($form);
        $this->assertSame([2, 4], $result);
        $reflection = new \ReflectionProperty($form, 'id_brand');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
        $reflection = new \ReflectionProperty($form, 'active');
        $result = $reflection->getValue($form);
        $this->assertSame(true, $result);
        $reflection = new \ReflectionProperty($form, 'total_products');
        $result = $reflection->getValue($form);
        $this->assertSame(568, $result);
        $reflection = new \ReflectionProperty($form, 'seocode');
        $result = $reflection->getValue($form);
        $this->assertSame('product', $result);
        $reflection = new \ReflectionProperty($form, 'related');
        $result = $reflection->getValue($form);
        $this->assertSame('1,2,3', $result);
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::EDIT]);
        $form->attributes = [
            'id'=>12,
            'code'=>'HJU780-R',
            'name'=>'Product 1',
            'short_description'=>'Short description 1',
            'description'=>'Description 1',
            'price'=>568.89,
            'images'=>'test',
            'id_category'=>1,
            'id_subcategory'=>2,
            'id_colors'=>[1, 2, 3],
            'id_sizes'=>[2, 4],
            'id_brand'=>1,
            'active'=>true,
            'total_products'=>568,
            'seocode'=>'product',
            'views'=>571,
            'related'=>'1,2,3'
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(12, $result);
        $reflection = new \ReflectionProperty($form, 'code');
        $result = $reflection->getValue($form);
        $this->assertSame('HJU780-R', $result);
        $reflection = new \ReflectionProperty($form, 'name');
        $result = $reflection->getValue($form);
        $this->assertSame('Product 1', $result);
        $reflection = new \ReflectionProperty($form, 'short_description');
        $result = $reflection->getValue($form);
        $this->assertSame('Short description 1', $result);
        $reflection = new \ReflectionProperty($form, 'description');
        $result = $reflection->getValue($form);
        $this->assertSame('Description 1', $result);
        $reflection = new \ReflectionProperty($form, 'price');
        $result = $reflection->getValue($form);
        $this->assertSame(568.89, $result);
        $reflection = new \ReflectionProperty($form, 'images');
        $result = $reflection->getValue($form);
        $this->assertSame('test', $result);
        $reflection = new \ReflectionProperty($form, 'id_category');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
        $reflection = new \ReflectionProperty($form, 'id_subcategory');
        $result = $reflection->getValue($form);
        $this->assertSame(2, $result);
        $reflection = new \ReflectionProperty($form, 'id_colors');
        $result = $reflection->getValue($form);
        $this->assertSame([1, 2, 3], $result);
        $reflection = new \ReflectionProperty($form, 'id_sizes');
        $result = $reflection->getValue($form);
        $this->assertSame([2, 4], $result);
        $reflection = new \ReflectionProperty($form, 'id_brand');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
        $reflection = new \ReflectionProperty($form, 'active');
        $result = $reflection->getValue($form);
        $this->assertSame(true, $result);
        $reflection = new \ReflectionProperty($form, 'total_products');
        $result = $reflection->getValue($form);
        $this->assertSame(568, $result);
        $reflection = new \ReflectionProperty($form, 'seocode');
        $result = $reflection->getValue($form);
        $this->assertSame('product', $result);
        $reflection = new \ReflectionProperty($form, 'views');
        $result = $reflection->getValue($form);
        $this->assertSame(571, $result);
        $reflection = new \ReflectionProperty($form, 'related');
        $result = $reflection->getValue($form);
        $this->assertSame('1,2,3', $result);
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::GET]);
        $form->attributes = [
            'id'=>12,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(12, $result);
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::DELETE]);
        $form->attributes = [
            'id'=>12,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(12, $result);
    }
    
    /**
     * Тестирует метод AdminProductForm::rules
     */
    public function testRules()
    {
        $form = new AdminProductForm(['scenario'=>AdminProductForm::CREATE]);
        $form->validate();
        
        $this->assertCount(11, $form->errors);
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::CREATE]);
        $form->attributes = [
            'code'=>self::$dbClass->products['product_1']['code'],
            'name'=>'Product 1',
            'short_description'=>'Short description 1',
            'description'=>'Description 1',
            'price'=>568.89,
            'images'=>'test',
            'id_category'=>1,
            'id_subcategory'=>2,
            'id_colors'=>[1, 2, 3],
            'id_sizes'=>[2, 4],
            'id_brand'=>1,
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::CREATE]);
        $form->attributes = [
            'code'=>'HJU780-R',
            'name'=>'Product 1',
            'short_description'=>'Short description 1',
            'description'=>'Description 1',
            'price'=>568.89,
            'images'=>'test',
            'id_category'=>1,
            'id_subcategory'=>2,
            'id_colors'=>[1, 2, 3],
            'id_sizes'=>[2, 4],
            'id_brand'=>1,
        ];
        
        $this->assertEmpty($form->errors);
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::EDIT]);
        $form->validate();
        
        $this->assertCount(12, $form->errors);
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::EDIT]);
        $form->attributes = [
            'id'=>1,
            'code'=>'HJU780-R',
            'name'=>'Product 1',
            'short_description'=>'Short description 1',
            'description'=>'Description 1',
            'price'=>568.89,
            'id_category'=>1,
            'id_subcategory'=>2,
            'id_colors'=>[1, 2, 3],
            'id_sizes'=>[2, 4],
            'id_brand'=>1,
            'seocode'=>'product', 
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::EDIT]);
        $form->attributes = [
            'id'=>self::$dbClass->products['product_1']['id'],
            'code'=>self::$dbClass->products['product_1']['code'],
            'name'=>'Product 1',
            'short_description'=>'Short description 1',
            'description'=>'Description 1',
            'price'=>568.89,
            'id_category'=>1,
            'id_subcategory'=>2,
            'id_colors'=>[1, 2, 3],
            'id_sizes'=>[2, 4],
            'id_brand'=>1,
            'seocode'=>'product', 
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::EDIT]);
        $form->attributes = [
            'id'=>self::$dbClass->products['product_2']['id'],
            'code'=>self::$dbClass->products['product_1']['code'],
            'name'=>'Product 1',
            'short_description'=>'Short description 1',
            'description'=>'Description 1',
            'price'=>568.89,
            'id_category'=>1,
            'id_subcategory'=>2,
            'id_colors'=>[1, 2, 3],
            'id_sizes'=>[2, 4],
            'id_brand'=>1,
            'seocode'=>'product', 
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
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
                        0=>'/var/www/html/shop/tests/sources/images/1.jpg', 
                        1=>'/var/www/html/shop/tests/sources/images/3.jpg'
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
        
        $_FILES = $filesArray;
        $imagesToLoad = UploadedFile::getInstancesByName('AdminProductForm[images]');
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::EDIT]);
        $form->attributes = [
            'id'=>1,
            'code'=>'HJU780-R',
            'name'=>'Product 1',
            'short_description'=>'Short description 1',
            'description'=>'Description 1',
            'price'=>568.89,
            'id_category'=>1,
            'id_subcategory'=>2,
            'id_colors'=>[1, 2, 3],
            'id_sizes'=>[2, 4],
            'id_brand'=>1,
            'seocode'=>'product',
            'images'=>$imagesToLoad
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        
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
                        1=>1024*400
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
        
        $reflection = new \ReflectionProperty(UploadedFile::class, '_files');
        $reflection->setAccessible(true);
        $reflection->setValue(null);
        
        $_FILES = $filesArray;
        $imagesToLoad = UploadedFile::getInstancesByName('AdminProductForm[images]');
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::EDIT]);
        $form->attributes = [
            'id'=>1,
            'code'=>'HJU780-R',
            'name'=>'Product 1',
            'short_description'=>'Short description 1',
            'description'=>'Description 1',
            'price'=>568.89,
            'id_category'=>1,
            'id_subcategory'=>2,
            'id_colors'=>[1, 2, 3],
            'id_sizes'=>[2, 4],
            'id_brand'=>1,
            'seocode'=>'product',
            'images'=>$imagesToLoad
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::GET]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::GET]);
        $form->attributes = [
            'id'=>7,
        ];
        
        $this->assertEmpty($form->errors);
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::DELETE]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        
        $form = new AdminProductForm(['scenario'=>AdminProductForm::DELETE]);
        $form->attributes = [
            'id'=>7,
        ];
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}

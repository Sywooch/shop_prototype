<?php

namespace app\test\models;

use yii\helpers\Url;
use yii\web\UploadedFile;
use app\tests\DbManager;
use app\models\{ProductsModel, 
    ColorsModel, 
    SizesModel, 
    CommentsModel,
    CategoriesModel,
    SubcategoryModel};
use app\helpers\MappersHelper;

/**
 * Тестирует ProductsModel
 */
class ProductsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_date = 1462453595;
    private static $_code = 'YU-6709';
    private static $_name = 'name';
    private static $_description = 'description';
    private static $_price = 14.45;
    private static $_images = 'images';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_quantity = 1;
    private static $_sizeToCart = 23;
    private static $_colorToCart = 2;
    private static $_color = 'gray';
    private static $_size = '46';
    private static $_text = 'Some text';
    private static $_email = 'some@some.com';
    private static $_hash;
    private static $_content = 'some content';
    private static $_active = true;
    
    private static $_filesArray = [
        'ProductsModel' => [
            'name' => [
                'imagesToLoad'=>[
                    0=>'1.jpg', 
                    1=>'2.jpg'
                ]
            ],
            'type' => [
                'imagesToLoad'=>[
                    0=>'image/jpeg', 
                    1=>'image/jpeg'
                ]
            ],
            'tmp_name' => [
                'imagesToLoad'=>[
                    0=>'/var/www/html/shop/tests/source/images/2.jpg', 
                    1=>'/var/www/html/shop/tests/source/images/3.jpg'
                ]
            ],
            'size' => [
                'imagesToLoad' => [
                    0=>11037,
                    1=>(1024*1024)*3
                ]
            ],
            'error' => [
                'imagesToLoad' => [
                    0=>0,
                    1=>0,
                ]
            ],
        ],
    ];
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\ProductsModel');
        
        self::$_hash = md5('some');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':date'=>self::$_date, ':code'=>self::$_code, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id, ':color'=>self::$_color]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_colors}} SET [[id_products]]=:id_products, [[id_colors]]=:id_colors');
        $command->bindValues([':id_products'=>self::$_id, ':id_colors'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_sizes}} SET [[id_products]]=:id_products, [[id_sizes]]=:id_sizes');
        $command->bindValues([':id_products'=>self::$_id, ':id_sizes'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id + 1, ':date'=>self::$_date, ':code'=>self::$_code . 'n', ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_colors}} SET [[id_products]]=:id_products, [[id_colors]]=:id_colors');
        $command->bindValues([':id_products'=>self::$_id + 1, ':id_colors'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_sizes}} SET [[id_products]]=:id_products, [[id_sizes]]=:id_sizes');
        $command->bindValues([':id_products'=>self::$_id + 1, ':id_sizes'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{related_products}} SET [[id_products]]=:id_products, [[id_related_products]]=:id_related_products');
        $command->bindValues([':id_products'=>self::$_id, ':id_related_products'=>self::$_id + 1]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{comments}} SET [[id]]=:id, [[text]]=:text, [[name]]=:name, [[id_emails]]=:id_emails, [[id_products]]=:id_products, [[active]]=:active');
        $command->bindValues([':id'=>self::$_id, ':text'=>self::$_text, ':name'=>self::$_name, ':id_emails'=>self::$_id, ':id_products'=>self::$_id, ':active'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new ProductsModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_LIST_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM_TO_CART'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM_FOR_REMOVE'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM_FOR_CLEAR_CART'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_PRODUCT_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM_FOR_UPDATE'));
        
        $this->assertTrue(property_exists($model, '_id'));
        $this->assertTrue(property_exists($model, '_date'));
        $this->assertTrue(property_exists($model, 'code'));
        $this->assertTrue(property_exists($model, 'name'));
        $this->assertTrue(property_exists($model, 'description'));
        $this->assertTrue(property_exists($model, 'short_description'));
        $this->assertTrue(property_exists($model, 'price'));
        $this->assertTrue(property_exists($model, 'active'));
        $this->assertTrue(property_exists($model, 'images'));
        $this->assertTrue(property_exists($model, 'imagesToLoad'));
        $this->assertTrue(property_exists($model, 'id_categories'));
        $this->assertTrue(property_exists($model, 'id_subcategory'));
        $this->assertTrue(property_exists($model, '_categories'));
        $this->assertTrue(property_exists($model, '_subcategory'));
        $this->assertTrue(property_exists($model, 'hash'));
        $this->assertTrue(property_exists($model, 'colorToCart'));
        $this->assertTrue(property_exists($model, 'sizeToCart'));
        $this->assertTrue(property_exists($model, 'quantity'));
        $this->assertTrue(property_exists($model, '_colors'));
        $this->assertTrue(property_exists($model, '_sizes'));
        $this->assertTrue(property_exists($model, '_similar'));
        $this->assertTrue(property_exists($model, '_related'));
        $this->assertTrue(property_exists($model, '_comments'));
        $this->assertTrue(property_exists($model, '_categoriesObject'));
        $this->assertTrue(property_exists($model, '_subcategoryObject'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_LIST_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'date'=>self::$_date, 'code'=>self::$_code, 'name'=>self::$_name, 'description'=>self::$_description, 'short_description'=>self::$_description, 'price'=>self::$_price, 'images'=>self::$_images, 'categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode, 'colorToCart'=>self::$_colorToCart,  'id_categories'=>self::$_id, 'id_subcategory'=>self::$_id, 'active'=>self::$_active];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->date));
        $this->assertFalse(empty($model->code));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->description));
        $this->assertFalse(empty($model->short_description));
        $this->assertFalse(empty($model->price));
        $this->assertFalse(empty($model->images));
        $this->assertFalse(empty($model->categories));
        $this->assertFalse(empty($model->subcategory));
        $this->assertFalse(empty($model->id_categories));
        $this->assertFalse(empty($model->id_subcategory));
        $this->assertFalse(empty($model->active));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_date, $model->date);
        $this->assertEquals(self::$_code, $model->code);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_description, $model->description);
        $this->assertEquals(self::$_description, $model->short_description);
        $this->assertEquals(self::$_price, $model->price);
        $this->assertEquals(self::$_images, $model->images);
        $this->assertEquals(self::$_categorySeocode, $model->categories);
        $this->assertEquals(self::$_subcategorySeocode, $model->subcategory);
        $this->assertEquals(self::$_id, $model->id_categories);
        $this->assertEquals(self::$_id, $model->id_subcategory);
        $this->assertEquals(self::$_active, $model->active);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
        $model->attributes = ['id'=>self::$_id, 'code'=>self::$_code, 'name'=>self::$_name, 'description'=>self::$_description, 'price'=>self::$_price, 'images'=>self::$_images, 'colorToCart'=>self::$_colorToCart, 'sizeToCart'=>self::$_sizeToCart, 'quantity'=>self::$_quantity, 'categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode, 'hash'=>self::$_hash];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->code));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->description));
        $this->assertFalse(empty($model->price));
        $this->assertFalse(empty($model->images));
        $this->assertFalse(empty($model->colorToCart));
        $this->assertFalse(empty($model->sizeToCart));
        $this->assertFalse(empty($model->quantity));
        $this->assertFalse(empty($model->categories));
        $this->assertFalse(empty($model->subcategory));
        $this->assertFalse(empty($model->hash));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_code, $model->code);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_description, $model->description);
        $this->assertEquals(self::$_price, $model->price);
        $this->assertEquals(self::$_images, $model->images);
        $this->assertEquals(self::$_colorToCart, $model->colorToCart);
        $this->assertEquals(self::$_sizeToCart, $model->sizeToCart);
        $this->assertEquals(self::$_quantity, $model->quantity);
        $this->assertEquals(self::$_categorySeocode, $model->categories);
        $this->assertEquals(self::$_subcategorySeocode, $model->subcategory);
        $this->assertEquals(self::$_hash, $model->hash);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_REMOVE]);
        $model->attributes = ['id'=>self::$_id, 'hash'=>self::$_hash];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->hash));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_hash, $model->hash);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_CLEAR_CART]);
        $model->attributes = ['id'=>self::$_id, 'categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode, 'code'=>self::$_code];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->categories));
        $this->assertFalse(empty($model->subcategory));
        $this->assertTrue(empty($model->code));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_categorySeocode, $model->categories);
        $this->assertEquals(self::$_subcategorySeocode, $model->subcategory);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT_FORM]);
        $model->attributes = ['code'=>self::$_code, 'name'=>self::$_name, 'description'=>self::$_description, 'short_description'=>self::$_description, 'price'=>self::$_price, 'imagesToLoad'=>[self::$_images], 'id_categories'=>self::$_id, 'id_subcategory'=>self::$_id];
        
        $this->assertFalse(empty($model->code));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->description));
        $this->assertFalse(empty($model->short_description));
        $this->assertFalse(empty($model->price));
        $this->assertFalse(empty($model->imagesToLoad));
        $this->assertFalse(empty($model->id_categories));
        $this->assertFalse(empty($model->id_subcategory));
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_UPDATE]);
        $model->attributes = ['id'=>self::$_id, 'date'=>self::$_date, 'code'=>self::$_code, 'name'=>self::$_name, 'description'=>self::$_description, 'short_description'=>self::$_description, 'price'=>self::$_price, 'images'=>self::$_images, 'id_categories'=>self::$_id, 'id_subcategory'=>self::$_id, 'active'=>self::$_active];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->date));
        $this->assertFalse(empty($model->code));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->description));
        $this->assertFalse(empty($model->short_description));
        $this->assertFalse(empty($model->price));
        $this->assertFalse(empty($model->images));
        $this->assertFalse(empty($model->id_categories));
        $this->assertFalse(empty($model->id_subcategory));
        $this->assertFalse(empty($model->active));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_date, $model->date);
        $this->assertEquals(self::$_code, $model->code);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_description, $model->description);
        $this->assertEquals(self::$_description, $model->short_description);
        $this->assertEquals(self::$_price, $model->price);
        $this->assertEquals(self::$_images, $model->images);
        $this->assertEquals(self::$_id, $model->id_categories);
        $this->assertEquals(self::$_id, $model->id_subcategory);
        $this->assertEquals(self::$_active, $model->active);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(8, count($model->errors));
        $this->assertTrue(array_key_exists('code', $model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertTrue(array_key_exists('description', $model->errors));
        $this->assertTrue(array_key_exists('short_description', $model->errors));
        $this->assertTrue(array_key_exists('price', $model->errors));
        $this->assertTrue(array_key_exists('imagesToLoad', $model->errors));
        $this->assertTrue(array_key_exists('id_categories', $model->errors));
        $this->assertTrue(array_key_exists('id_subcategory', $model->errors));
        
        $_FILES = self::$_filesArray;
        $imagesToLoad = UploadedFile::getInstancesByName('ProductsModel[imagesToLoad]');
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT_FORM]);
        $model->attributes = ['code'=>self::$_code, 'name'=>self::$_name, 'description'=>self::$_description, 'short_description'=>self::$_description, 'price'=>self::$_price, 'imagesToLoad'=>$imagesToLoad, 'id_categories'=>self::$_id, 'id_subcategory'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('imagesToLoad', $model->errors));
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT_FORM]);
        $model->attributes = ['code'=>'<p>' . self::$_code . '</p>', 'name'=>'<script src="/my/script.js"></script>' . self::$_name, 'description'=>'<p>' . self::$_description . '</p>', 'short_description'=>'<script src="/my/script.js"></script>' . self::$_description, 'price'=>self::$_price, 'imagesToLoad'=>$imagesToLoad, 'id_categories'=>self::$_id, 'id_subcategory'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(self::$_code, $model->code);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_description, $model->short_description);
        $this->assertEquals('<p>' . self::$_description . '</p>', $model->description);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_UPDATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(9, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        $this->assertTrue(array_key_exists('code', $model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertTrue(array_key_exists('description', $model->errors));
        $this->assertTrue(array_key_exists('short_description', $model->errors));
        $this->assertTrue(array_key_exists('price', $model->errors));
        $this->assertTrue(array_key_exists('images', $model->errors));
        $this->assertTrue(array_key_exists('id_categories', $model->errors));
        $this->assertTrue(array_key_exists('id_subcategory', $model->errors));
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_UPDATE]);
        $model->attributes = ['code'=>'<p>' . self::$_code . '</p>', 'name'=>'<script src="/my/script.js"></script>' . self::$_name, 'description'=>'<p>' . self::$_description . '</p>', 'short_description'=>'<script src="/my/script.js"></script>' . self::$_description, 'price'=>self::$_price, 'imagesToLoad'=>$imagesToLoad, 'id_categories'=>self::$_id, 'id_subcategory'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(self::$_code, $model->code);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_description, $model->short_description);
        $this->assertEquals('<p>' . self::$_description . '</p>', $model->description);
    }
    
    /**
     * Тестирует метод ProductsModel::setId
     */
    public function testSetId()
    {
        $model = new ProductsModel();
        $model->id = self::$_id;
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует метод ProductsModel::getId
     */
    public function testGetId()
    {
        $model = new ProductsModel();
        $model->code = self::$_code;
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует возврат null в методе ProductsModel::getId
     * при условии, что необходимые для выполнения свойства пусты
     */
    public function testNullGetId()
    {
        $model = new ProductsModel();
        
       $this->assertTrue(is_null($model->id));
    }
    
    /**
     * Тестирует метод ProductsModel::getColors
     */
    public function testGetColors()
    {
        $model = new ProductsModel();
        $model->id = self::$_id;
        
        $colorsArray = $model->colors;
        
        $this->assertTrue(is_array($colorsArray));
        $this->assertFalse(empty($colorsArray));
        $this->assertTrue(is_object($colorsArray[0]));
        $this->assertTrue($colorsArray[0] instanceof ColorsModel);
    }
    
    /**
     * Тестирует возврат null в методе ProductsModel::getColors
     * при условии, что необходимые для выполнения свойства пусты
     */
    public function testNullGetColors()
    {
        $model = new ProductsModel();
        
        $this->assertTrue(is_null($model->colors));
    }
    
    /**
     * Тестирует метод ProductsModel::getSizes
     */
    public function testGetSizes()
    {
        $model = new ProductsModel();
        $model->id = self::$_id;
        
        $sizesArray = $model->sizes;
        
        $this->assertTrue(is_array($sizesArray));
        $this->assertFalse(empty($sizesArray));
        $this->assertTrue(is_object($sizesArray[0]));
        $this->assertTrue($sizesArray[0] instanceof SizesModel);
    }
    
    /**
     * Тестирует возврат null в методе ProductsModel::getSizes
     * при условии, что необходимые для выполнения свойства пусты
     */
    public function testNullGetSizes()
    {
        $model = new ProductsModel();
        
        $this->assertTrue(is_null($model->sizes));
    }
    
    /**
     * Тестирует метод ProductsModel::getSimilar
     */
    public function testGetSimilar()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $model = new ProductsModel();
        $model->id = self::$_id;
        
        $similarArray = $model->similar;
        
        $this->assertTrue(is_array($similarArray));
        $this->assertFalse(empty($similarArray));
        $this->assertTrue(is_object($similarArray[0]));
        $this->assertTrue($similarArray[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует возврат null в методе ProductsModel::getSimilar
     * при условии, что необходимые для выполнения свойства пусты
     */
    public function testNullGetSimilar()
    {
        $model = new ProductsModel();
        
        $this->assertTrue(is_null($model->similar));
    }
    
    /**
     * Тестирует метод ProductsModel::getRelated
     */
    public function testGetRelated()
    {
        $model = new ProductsModel();
        $model->id = self::$_id;
        
        $relatedArray = $model->related;
        
        $this->assertTrue(is_array($relatedArray));
        $this->assertFalse(empty($relatedArray));
        $this->assertTrue(is_object($relatedArray[0]));
        $this->assertTrue($relatedArray[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует возврат null в методе ProductsModel::getRelated
     * при условии, что необходимые для выполнения свойства пусты
     */
    public function testNullGetRelated()
    {
        $model = new ProductsModel();
        
        $this->assertTrue(is_null($model->related));
    }
    
    /**
     * Тестирует метод ProductsModel::getComments
     */
    public function testGetComments()
    {
        $model = new ProductsModel();
        $model->id = self::$_id;
        
        $commentsArray = $model->comments;
        
        $this->assertTrue(is_array($commentsArray));
        $this->assertFalse(empty($commentsArray));
        $this->assertTrue(is_object($commentsArray[0]));
        $this->assertTrue($commentsArray[0] instanceof CommentsModel);
    }
    
    /**
     * Тестирует возврат null в методе ProductsModel::getComments
     * при условии, что необходимые для выполнения свойства пусты
     */
    public function testNullGetComments()
    {
        $model = new ProductsModel();
        
        $this->assertTrue(is_null($model->comments));
    }
    
    /**
     * Тестирует метод ProductsModel::getHash
     */
    public function testGetHash()
    {
        $model = new ProductsModel();
        $model->id = self::$_id;
        $model->code = self::$_code;
        $model->colorToCart = self::$_colorToCart;
        $model->sizeToCart = self::$_sizeToCart;
        
        $this->assertTrue(empty($model->hash));
        
        $model->getHash();
        
        $this->assertFalse(empty($model->hash));
    }
    
    /**
     * Тестирует метод ProductsModel::setCategories
     */
    public function testSetCategories()
    {
        $model = new ProductsModel();
        $model->categories = self::$_categorySeocode;
        
        $this->assertEquals(self::$_categorySeocode, $model->categories);
        $this->assertFalse(empty($model->categoriesObject));
        $this->assertTrue($model->categoriesObject instanceof CategoriesModel);
    }
    
    /**
     * Тестирует метод ProductsModel::getCategories
     */
    public function testGetCategories()
    {
        $model = new ProductsModel();
        $model->id_categories = self::$_id;
        
        $this->assertEquals(self::$_categorySeocode, $model->categories);
        $this->assertFalse(empty($model->categoriesObject));
        $this->assertTrue($model->categoriesObject instanceof CategoriesModel);
    }
    
    /**
     * Тестирует метод ProductsModel::setCategoriesObject
     */
    public function testSetCategoriesObject()
    {
        $categoriesModel = new CategoriesModel(['seocode'=>self::$_categorySeocode]);
        
        $model = new ProductsModel();
        $model->categoriesObject = $categoriesModel;
        
        $this->assertFalse(empty($model->categoriesObject));
        $this->assertTrue($model->categoriesObject instanceof CategoriesModel);
        $this->assertEquals(self::$_categorySeocode, $model->categories);
    }
    
    /**
     * Тестирует метод ProductsModel::getCategoriesObject
     */
    public function testGetCategoriesObject()
    {
        $model = new ProductsModel();
        $model->id_categories = self::$_id;
        
        $this->assertFalse(empty($model->categoriesObject));
        $this->assertTrue($model->categoriesObject instanceof CategoriesModel);
        $this->assertEquals(self::$_categorySeocode, $model->categories);
    }
    
    /**
     * Тестирует метод ProductsModel::getCategories
     * при условии, что необходимые для выполнения свойства пусты
     */
    public function testNullGetCategories()
    {
        $model = new ProductsModel();
        
       $this->assertTrue(is_null($model->categories));
    }
    
    /**
     * Тестирует метод ProductsModel::setSubcategory
     */
    public function testSetSubcategory()
    {
        $model = new ProductsModel();
        $model->subcategory = self::$_subcategorySeocode;
        
        $this->assertEquals(self::$_subcategorySeocode, $model->subcategory);
        $this->assertFalse(empty($model->subcategoryObject));
        $this->assertTrue($model->subcategoryObject instanceof SubcategoryModel);
    }
    
    /**
     * Тестирует метод ProductsModel::getSubcategory
     */
    public function testGetSubcategory()
    {
        $model = new ProductsModel();
        $model->id_subcategory = self::$_id;
        
        $this->assertEquals(self::$_subcategorySeocode, $model->subcategory);
        $this->assertFalse(empty($model->subcategoryObject));
        $this->assertTrue($model->subcategoryObject instanceof SubcategoryModel);
    }
    
    /**
     * Тестирует метод ProductsModel::setSubcategoryObject
     */
    public function testSetSubcategoryObject()
    {
        $subcategoryModel = new SubcategoryModel(['seocode'=>self::$_subcategorySeocode]);
        
        $model = new ProductsModel();
        $model->subcategoryObject = $subcategoryModel;
        
        $this->assertFalse(empty($model->subcategoryObject));
        $this->assertTrue($model->subcategoryObject instanceof SubcategoryModel);
        $this->assertEquals(self::$_subcategorySeocode, $model->subcategory);
    }
    
    /**
     * Тестирует метод ProductsModel::getSubcategoryObject
     */
    public function testGetSubcategoryObject()
    {
        $model = new ProductsModel();
        $model->id_subcategory = self::$_id;
        
        $this->assertFalse(empty($model->subcategoryObject));
        $this->assertTrue($model->subcategoryObject instanceof SubcategoryModel);
        $this->assertEquals(self::$_subcategorySeocode, $model->subcategory);
    }
    
    /**
     * Тестирует метод ProductsModel::setDate
     */
    public function testSetDate()
    {
        $date = time();
        
        $model = new ProductsModel();
        $model->date = $date;
        
        $this->assertEquals($date, $model->date);
    }
    
    /**
     * Тестирует метод ProductsModel::getDate
     */
    public function testGetDate()
    {
        $model = new ProductsModel();
        
        $this->assertFalse(empty($model->date));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}

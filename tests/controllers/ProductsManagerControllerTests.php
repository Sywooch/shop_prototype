<?php

namespace app\tests\controllers;

use GuzzleHttp\Client;
use yii\helpers\ArrayHelper;

/**
 * Тестирует класс ProductsManagerController
 */
class ProductsManagerControllerTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_guzzleClient;
    
    private static $_categoriesId = 1;
    private static $_subcategoryId = 1;
    private static $_brandsId = 1;
    private static $_colorsId = 1;
    private static $_sizesId = 1;
    
    private static $_productId = null;
    private static $_dirPath = null;
    private static $_imagesInDirPath = null;
    
    private static $_code = 'Nw-1234';
    private static $_name = 'Name';
    private static $_description = 'Some description';
    private static $_price = 14.45;
    private static $_file1 = '/var/www/html/shop/tests/source/images/2.jpg';
    private static $_file2 = '/var/www/html/shop/tests/source/images/3.jpg';
    private static $_imagePath;
    private static $_brand = 'Some Brand';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_color = 'gray';
    private static $_size = '46';
    
    public static function setUpBeforeClass()
    {
        require(__DIR__ . '/../../config/db.php');
        self::$_dbClass = new \PDO($config['dsn'] . ';charset=' . $config['charset'], $config['username'], $config['password']);
        
        self::$_imagePath = \Yii::getAlias('@pic');
        
        self::$_guzzleClient = new Client();
        
        $pdoStatement = self::$_dbClass->prepare('SELECT id FROM categories LIMIT 1');
        $pdoStatement->execute();
        $result = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        if (is_array($result) && !empty($result) && !empty($result['id'])) {
            self::$_categoriesId = $result['id'];
        } else {
            $pdoStatement = self::$_dbClass->prepare('INSERT INTO categories SET id=:id, name=:name, seocode=:seocode');
            $pdoStatement->execute([':id'=>self::$_categoriesId, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        }
        
        $pdoStatement = self::$_dbClass->prepare('SELECT id FROM subcategory WHERE id_categories=:id_categories LIMIT 1');
        $pdoStatement->execute([':id_categories'=>self::$_categoriesId]);
        $result = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        if (is_array($result) && !empty($result) && !empty($result['id'])) {
            self::$_subcategoryId = $result['id'];
        } else {
            $pdoStatement = self::$_dbClass->prepare('INSERT INTO subcategory SET id=:id, name=:name, id_categories=:id_categories, seocode=:seocode');
            $pdoStatement->execute([':id'=>self::$_subcategoryId, ':name'=>self::$_name, ':id_categories'=>self::$_categoriesId, ':seocode'=>self::$_categorySeocode]);
        }
        
        $pdoStatement = self::$_dbClass->prepare('SELECT id FROM brands LIMIT 1');
        $pdoStatement->execute();
        $result = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        if (is_array($result) && !empty($result) && !empty($result['id'])) {
            self::$_brandsId = $result['id'];
        } else {
            $pdoStatement = self::$_dbClass->prepare('INSERT INTO brands SET id=:id, brand=:brand');
            $pdoStatement->execute([':id'=>self::$_brandsId, ':brand'=>self::$_brand]);
        }
        
        $pdoStatement = self::$_dbClass->prepare('SELECT id FROM colors LIMIT 1');
        $pdoStatement->execute();
        $result = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        if (is_array($result) && !empty($result) && !empty($result['id'])) {
            self::$_colorsId = $result['id'];
        } else {
            $pdoStatement = self::$_dbClass->prepare('INSERT INTO colors SET id=:id, color=:color');
            $pdoStatement->execute([':id'=>self::$_colorsId, ':color'=>self::$_color]);
        }
        
        $pdoStatement = self::$_dbClass->prepare('SELECT id FROM sizes LIMIT 1');
        $pdoStatement->execute();
        $result = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        if (is_array($result) && !empty($result) && !empty($result['id'])) {
            self::$_sizesId = $result['id'];
        } else {
            $pdoStatement = self::$_dbClass->prepare('INSERT INTO colors SET id=:id, size=:size');
            $pdoStatement->execute([':id'=>self::$_sizesId, ':size'=>self::$_size]);
        }
    }
    
    /**
     * Тестирует метод ProductsManagerController::actionAddProduct
     * при обработке GET запроса
     */
    public function testGetActionAddProduct()
    {
        $response = self::$_guzzleClient->request('GET', 'http://shop.com/add-product');
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
    }
    
    /**
     * Тестирует метод ProductsManagerController::actionAddProduct
     * при обработке POST запроса
     */
    public function testPostActionAddProduct()
    {
        $response = self::$_guzzleClient->request('POST', 'http://shop.com/add-product', [
            'query'=>['csrfdisable'=>true],
            'allow_redirects' => false,
            'multipart'=>[
                [
                    'name'=>'ProductsModel[code]',
                    'contents'=>self::$_code,
                ],
                [
                    'name'=>'ProductsModel[name]',
                    'contents'=>self::$_name,
                ],
                [
                    'name'=>'ProductsModel[description]',
                    'contents'=>self::$_description,
                ],
                [
                    'name'=>'ProductsModel[price]',
                    'contents'=>self::$_price,
                ],
                [
                    'name'=>'ProductsModel[id_categories]',
                    'contents'=>self::$_categoriesId,
                ],
                [
                    'name'=>'ProductsModel[id_subcategory]',
                    'contents'=>self::$_subcategoryId,
                ],
                [
                    'name'=>'ProductsModel[imagesToLoad][]',
                    'contents'=>fopen(self::$_file1, 'r'),
                ],
                [
                    'name'=>'ProductsModel[imagesToLoad][]',
                    'contents'=>fopen(self::$_file2, 'r'),
                ],
                [
                    'name'=>'BrandsModel[id]',
                    'contents'=>self::$_brandsId,
                ],
                [
                    'name'=>'ColorsModel[idArray][]',
                    'contents'=>self::$_colorsId,
                ],
                [
                    'name'=>'SizesModel[idArray][]',
                    'contents'=>self::$_sizesId,
                ],
            ],
        ]);
        
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Found', $response->getReasonPhrase());
        
        $array = $response->getHeaders();
        self::$_productId = basename($array['Location'][0]);
        
        $pdoStatement = self::$_dbClass->prepare('SELECT * FROM products WHERE id=:id');
        $pdoStatement->execute([':id'=>self::$_productId]);
        $productArray = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        
        $this->assertFalse(empty($productArray));
        $this->assertTrue(is_array($productArray));
        $this->assertFalse(empty($productArray['date']));
        $this->assertFalse(empty($productArray['images']));
        $this->assertEquals(self::$_code, $productArray['code']);
        $this->assertEquals(self::$_name, $productArray['name']);
        $this->assertEquals(self::$_description, $productArray['description']);
        $this->assertEquals(self::$_price, $productArray['price']);
        $this->assertEquals(self::$_categoriesId, $productArray['id_categories']);
        $this->assertEquals(self::$_subcategoryId, $productArray['id_subcategory']);
        
        self::$_dirPath = self::$_imagePath . '/' . $productArray['images'];
        $this->assertTrue(file_exists(self::$_dirPath));
        $this->assertTrue(is_dir(self::$_dirPath));
        
        self::$_imagesInDirPath = glob(self::$_dirPath . '/[1-9]\.{jpg,gif,png}', GLOB_BRACE);
        $this->assertFalse(empty(self::$_imagesInDirPath));
        $this->assertEquals(2, count(self::$_imagesInDirPath));
        
        $pdoStatement = self::$_dbClass->prepare('SELECT * FROM products_brands WHERE id_products=:id_products AND id_brands=:id_brands');
        $pdoStatement->execute([':id_products'=>self::$_productId, ':id_brands'=>self::$_brandsId]);
        $brandsArray = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        
        $this->assertFalse(empty($brandsArray));
        $this->assertTrue(is_array($brandsArray));
        $this->assertEquals(self::$_productId, $brandsArray['id_products']);
        $this->assertEquals(self::$_brandsId, $brandsArray['id_brands']);
        
        $pdoStatement = self::$_dbClass->prepare('SELECT * FROM products_colors WHERE id_products=:id_products AND id_colors=:id_colors');
        $pdoStatement->execute([':id_products'=>self::$_productId, ':id_colors'=>self::$_colorsId]);
        $colorsArray = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        
        $this->assertFalse(empty($colorsArray));
        $this->assertTrue(is_array($colorsArray));
        $this->assertEquals(self::$_productId, $colorsArray['id_products']);
        $this->assertEquals(self::$_colorsId, $colorsArray['id_colors']);
        
        $pdoStatement = self::$_dbClass->prepare('SELECT * FROM products_sizes WHERE id_products=:id_products AND id_sizes=:id_sizes');
        $pdoStatement->execute([':id_products'=>self::$_productId, ':id_sizes'=>self::$_sizesId]);
        $sizesArray = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        
        $this->assertFalse(empty($sizesArray));
        $this->assertTrue(is_array($sizesArray));
        $this->assertEquals(self::$_productId, $sizesArray['id_products']);
        $this->assertEquals(self::$_sizesId, $sizesArray['id_sizes']);
    }
    
    /**
     * Тестирует метод ProductsManagerController::actionGetSubcategoryAjax
     */
    public function testActionGetSubcategoryAjax()
    {
        $response = self::$_guzzleClient->request('POST', 'http://shop.com/get-subcategory-ajax', [
            'headers'=>['X-Requested-With'=>'XMLHttpRequest'],
            'query'=>['csrfdisable'=>true],
            'form_params' => [
                'categoriesId'=>self::$_categoriesId,
            ],
        ]);
        
        $ajaxArray = json_decode($response->getBody(), true);
        
        $pdoStatement = self::$_dbClass->prepare('SELECT * FROM subcategory WHERE id_categories=:id_categories');
        $pdoStatement->execute([':id_categories'=>self::$_categoriesId]);
        $subcategoryDbArray = ArrayHelper::map($pdoStatement->fetchAll(\PDO::FETCH_ASSOC), 'id', 'name');
        
        $this->assertEquals(count($ajaxArray), count($subcategoryDbArray));
        
        foreach (array_keys($ajaxArray) as $key) {
            $this->assertEquals($ajaxArray[$key], $subcategoryDbArray[$key]);
        }
    }
    
    public static function tearDownAfterClass()
    {
        if (!empty(self::$_productId)) {
            $pdoStatement = self::$_dbClass->prepare('DELETE FROM products WHERE id=:id');
            $pdoStatement->execute([':id'=>self::$_productId]);
        }
        
        if (!empty(self::$_imagesInDirPath)) {
            foreach (self::$_imagesInDirPath as $img) {
                unlink($img);
            }
        }
        
        if (!empty(self::$_dirPath)) {
            rmdir(self::$_dirPath);
        }
    }
}

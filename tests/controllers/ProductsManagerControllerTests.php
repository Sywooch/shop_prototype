<?php

namespace app\tests\controllers;

use app\tests\DbManager;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * Тестирует класс ProductsManagerController
 */
class ProductsManagerControllerTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_code = 'Nw-1234';
    private static $_name = 'Name';
    private static $_description = 'Some description';
    private static $_price = 14.45;
    private static $_file1 = '/var/www/html/shop/tests/source/images/2.jpg';
    private static $_file2 = '/var/www/html/shop/tests/source/images/3.jpg';
    private static $_imagePath = '/var/www/html/shop/tests/source/images/products/[0-9]*';
    private static $_brand = 'Some Brand';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_color = 'gray';
    private static $_size = '46';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{brands}} SET [[id]]=:id, [[brand]]=:brand');
        $command->bindValues([':id'=>self::$_id, ':brand'=>self::$_brand]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id, ':color'=>self::$_color]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
    }
    
    /**
     * Тестирует метод ProductsManagerController::actionAddProduct
     */
    public function testActionAddProduct()
    {
        $client = new Client();
        $response = $client->request('POST', 'http://shop.com/add-product', [
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
                    'contents'=>self::$_id,
                ],
                [
                    'name'=>'ProductsModel[id_subcategory]',
                    'contents'=>self::$_id,
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
                    'contents'=>self::$_id,
                ],
                [
                    'name'=>'ColorsModel[idArray][]',
                    'contents'=>self::$_id,
                ],
                [
                    'name'=>'SizesModel[idArray][]',
                    'contents'=>self::$_id,
                ],
            ],
        ]);
        
        $array = $response->getHeaders();
        $str = explode('/', $array['Location'][0]);
        $productID = $str[count($str) - 1];
        
        require(__DIR__ . '/../../config/db.php');
        
        $pdo = new \PDO('mysql:host=localhost;dbname=shop', 'shopadmin', 'shopadmin');
        $command = $pdo->prepare('SELECT * FROM products WHERE id=:id');
        $command->execute([':id'=>$productID]);
        print_r($command->fetchAll());
        
        /*$db = \Yii::$app->db;
        unset($config['class']);
        \Yii::configure($db, $config);
        $command = $db->createCommand('SELECT * FROM {{products}}');
        //$command->bindValue(':id', $productID);
        $result = $command->queryAll();
        print_r($result);*/
        
        /*$this->assertFalse(empty($dirsArray = glob(self::$_imagePath)));
        $this->assertEquals(1, count($dirsArray));
        
        foreach ($dirsArray as $dir) {
            $filesArray = glob($dir . '/[1-9]*');
            $this->assertEquals(2, count($filesArray));
            foreach ($filesArray as $file) {
                $this->assertTrue(in_array(pathinfo($file, PATHINFO_EXTENSION), ['png', 'jpg', 'gif']));
            }
        }*/
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}

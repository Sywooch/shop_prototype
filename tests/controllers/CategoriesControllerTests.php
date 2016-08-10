<?php

namespace app\tests\controllers;

use GuzzleHttp\Client;
use yii\helpers\ArrayHelper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс CategoriesController
 */
class CategoriesControllerTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_guzzleClient;
    
    private static $_categoriesId = 1;
    private static $_subcategoryId = 1;
    private static $_name = 'Name';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    private static $_categoriesAdd = null;
    private static $_subcategoryAdd = null;
    
    public static function setUpBeforeClass()
    {
        require(__DIR__ . '/../../config/db.php');
        self::$_dbClass = new \PDO($config['dsn'] . ';charset=' . $config['charset'], $config['username'], $config['password']);
        
        self::$_guzzleClient = new Client();
        
        $pdoStatement = self::$_dbClass->prepare('SELECT id FROM categories LIMIT 1');
        $pdoStatement->execute();
        $result = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        if (is_array($result) && !empty($result) && !empty($result['id'])) {
            self::$_categoriesId = $result['id'];
        } else {
            $pdoStatement = self::$_dbClass->prepare('INSERT INTO categories SET id=:id, name=:name, seocode=:seocode');
            $pdoStatement->execute([':id'=>self::$_categoriesId, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
            self::$_categoriesAdd = true;
        }
        
        $pdoStatement = self::$_dbClass->prepare('SELECT id FROM subcategory WHERE id_categories=:id_categories LIMIT 1');
        $pdoStatement->execute([':id_categories'=>self::$_categoriesId]);
        $result = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        if (is_array($result) && !empty($result) && !empty($result['id'])) {
            self::$_subcategoryId = $result['id'];
        } else {
            $pdoStatement = self::$_dbClass->prepare('INSERT INTO subcategory SET id=:id, name=:name, id_categories=:id_categories, seocode=:seocode');
            $pdoStatement->execute([':id'=>self::$_subcategoryId, ':name'=>self::$_name, ':id_categories'=>self::$_categoriesId, ':seocode'=>self::$_categorySeocode]);
            self::$_subcategoryAdd = true;
        }
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод CategoriesController::actionGetSubcategoryAjax
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
        if (!empty(self::$_categoriesAdd)) {
            $pdoStatement = self::$_dbClass->prepare('DELETE FROM categories WHERE id=:id');
            $pdoStatement->execute([':id'=>self::$_categoriesId]);
        }
        
        if (!empty(self::$_subcategoryAdd)) {
            $pdoStatement = self::$_dbClass->prepare('DELETE FROM subcayegory WHERE id=:id');
            $pdoStatement->execute([':id'=>self::$_subcategoryId]);
        }
    }
}

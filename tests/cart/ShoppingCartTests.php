<?php

namespace app\tests\cart;

use app\cart\ShoppingCart;
use app\models\ProductsModel;

/**
 * Тестирует класс app\cart\ShoppingCart
 */
class ShoppingCartTests extends \PHPUnit_Framework_TestCase
{
    /**
    * Тестирует метод app\cart\ShoppingCart::addProduct()
    */
    public function testAddProduct()
    {
        ShoppingCart::clearProductsArray();
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
        $model->attributes = ['id'=>1, 'name'=>'Some name', 'description'=>'Some description', 'price'=>234.78, 'colorToCart'=>1, 'sizeToCart'=>3, 'quantity'=>2, 'categories'=>'mensfootwear', 'subcategory'=>'snickers'];
        
        $this->assertTrue(empty(ShoppingCart::getProductsArray()));
        
        ShoppingCart::addProduct($model);
        
        $this->assertFalse(empty(ShoppingCart::getProductsArray()));
        
        $productsArray = ShoppingCart::getProductsArray();
        
        $this->assertTrue(is_object($productsArray[0]));
        $this->assertTrue($productsArray[0] instanceof ProductsModel);
        
        $this->assertTrue(property_exists($productsArray[0], 'id'));
        $this->assertTrue(property_exists($productsArray[0], 'name'));
        $this->assertTrue(property_exists($productsArray[0], 'description'));
        $this->assertTrue(property_exists($productsArray[0], 'price'));
        $this->assertTrue(property_exists($productsArray[0], 'colorToCart'));
        $this->assertTrue(property_exists($productsArray[0], 'sizeToCart'));
        $this->assertTrue(property_exists($productsArray[0], 'quantity'));
        $this->assertTrue(property_exists($productsArray[0], 'categories'));
        $this->assertTrue(property_exists($productsArray[0], 'subcategory'));
        
        $this->assertTrue(isset($productsArray[0]->id));
        $this->assertTrue(isset($productsArray[0]->name));
        $this->assertTrue(isset($productsArray[0]->description));
        $this->assertTrue(isset($productsArray[0]->price));
        $this->assertTrue(isset($productsArray[0]->colorToCart));
        $this->assertTrue(isset($productsArray[0]->sizeToCart));
        $this->assertTrue(isset($productsArray[0]->quantity));
        $this->assertTrue(isset($productsArray[0]->categories));
        $this->assertTrue(isset($productsArray[0]->subcategory));
    }
    
    /**
    * Тестирует метод app\cart\ShoppingCart::removeProduct()
    */
    public function testRemoveProduct()
    {
        ShoppingCart::clearProductsArray();
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
        $model->attributes = ['id'=>1, 'name'=>'Some name', 'description'=>'Some description', 'price'=>234.78, 'colorToCart'=>1, 'sizeToCart'=>3, 'quantity'=>2, 'categories'=>'mensfootwear', 'subcategory'=>'snickers'];
        
        $this->assertTrue(empty(ShoppingCart::getProductsArray()));
        
        ShoppingCart::addProduct($model);
        
        $this->assertFalse(empty(ShoppingCart::getProductsArray()));
        
        ShoppingCart::removeProduct($model);
        
        $this->assertTrue(empty(ShoppingCart::getProductsArray()));
    }
    
    /**
    * Тестирует метод app\cart\ShoppingCart::updateProduct()
    */
    public function testUpdateProduct()
    {
        ShoppingCart::clearProductsArray();
        
        $firstConfig = ['id'=>1, 'name'=>'Some name', 'description'=>'Some description', 'price'=>234.78, 'colorToCart'=>1, 'sizeToCart'=>3, 'quantity'=>2, 'categories'=>'mensfootwear', 'subcategory'=>'snickers'];
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
        $model->attributes = $firstConfig;
        
        $secondConfig = ['id'=>1, 'name'=>'Some name 2', 'description'=>'Some description 2', 'price'=>434.78, 'colorToCart'=>2, 'sizeToCart'=>2, 'quantity'=>1, 'categories'=>'mensfootwear', 'subcategory'=>'snickers'];
        $model2 = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
        $model2->attributes = $secondConfig;
        
        $this->assertTrue(empty(ShoppingCart::getProductsArray()));
        
        ShoppingCart::addProduct($model);
        
        $this->assertFalse(empty(ShoppingCart::getProductsArray()));
        
        $productsArray = ShoppingCart::getProductsArray();
        
        $this->assertEquals($firstConfig['id'], $productsArray[0]->id);
        $this->assertEquals($firstConfig['name'], $productsArray[0]->name);
        $this->assertEquals($firstConfig['description'], $productsArray[0]->description);
        $this->assertEquals($firstConfig['price'], $productsArray[0]->price);
        $this->assertEquals($firstConfig['colorToCart'], $productsArray[0]->colorToCart);
        $this->assertEquals($firstConfig['sizeToCart'], $productsArray[0]->sizeToCart);
        $this->assertEquals($firstConfig['quantity'], $productsArray[0]->quantity);
        $this->assertEquals($firstConfig['categories'], $productsArray[0]->categories);
        $this->assertEquals($firstConfig['subcategory'], $productsArray[0]->subcategory);
        
        ShoppingCart::updateProduct($model2);
        
        $this->assertEquals($secondConfig['id'], $productsArray[0]->id);
        $this->assertEquals($secondConfig['name'], $productsArray[0]->name);
        $this->assertEquals($secondConfig['description'], $productsArray[0]->description);
        $this->assertEquals($secondConfig['price'], $productsArray[0]->price);
        $this->assertEquals($secondConfig['colorToCart'], $productsArray[0]->colorToCart);
        $this->assertEquals($secondConfig['sizeToCart'], $productsArray[0]->sizeToCart);
        $this->assertEquals($secondConfig['quantity'], $productsArray[0]->quantity);
        $this->assertEquals($secondConfig['categories'], $productsArray[0]->categories);
        $this->assertEquals($secondConfig['subcategory'], $productsArray[0]->subcategory);
    }
    
    /**
    * Тестирует метод app\cart\ShoppingCart::setProductsArray()
    */
    public function testSetProductsArray()
    {
        ShoppingCart::clearProductsArray();
        
        $firstConfig = ['id'=>1, 'name'=>'Some name', 'description'=>'Some description', 'price'=>234.78, 'colorToCart'=>1, 'sizeToCart'=>3, 'quantity'=>2, 'categories'=>'mensfootwear', 'subcategory'=>'snickers'];
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
        $model->attributes = $firstConfig;
        
        $secondConfig = ['id'=>1, 'name'=>'Some name 2', 'description'=>'Some description 2', 'price'=>434.78, 'colorToCart'=>2, 'sizeToCart'=>2, 'quantity'=>1, 'categories'=>'mensfootwear', 'subcategory'=>'snickers'];
        $model2 = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
        $model2->attributes = $secondConfig;
        
        $this->assertTrue(empty(ShoppingCart::getProductsArray()));
        
        $arrayToInsert = [$model, $model2];
        
        ShoppingCart::setProductsArray($arrayToInsert);
        
        $this->assertFalse(empty(ShoppingCart::getProductsArray()));
        $this->assertEquals(2, count(ShoppingCart::getProductsArray()));
    }
    
    /**
    * Тестирует метод app\cart\ShoppingCart::getShortData()
    */
    public function testGetShortData()
    {
        ShoppingCart::clearProductsArray();
        
        $firstConfig = ['id'=>1, 'name'=>'Some name', 'description'=>'Some description', 'price'=>234.78, 'colorToCart'=>1, 'sizeToCart'=>3, 'quantity'=>2, 'categories'=>'mensfootwear', 'subcategory'=>'snickers'];
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
        $model->attributes = $firstConfig;
        
        ShoppingCart::addProduct($model);
        
        ShoppingCart::getShortData();
        
        $this->assertEquals(($firstConfig['price'] * $firstConfig['quantity']), ShoppingCart::getTotalCost());
        $this->assertEquals(($firstConfig['quantity']), ShoppingCart::getTotalProducts());
        
        $secondConfig = ['id'=>2, 'name'=>'Some name 2', 'description'=>'Some description 2', 'price'=>56.06, 'colorToCart'=>2, 'sizeToCart'=>2, 'quantity'=>1, 'categories'=>'mensfootwear', 'subcategory'=>'snickers'];
        $model2 = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
        $model2->attributes = $secondConfig;
        
        ShoppingCart::addProduct($model2);
        
        ShoppingCart::getShortData();
        
        $this->assertEquals((($firstConfig['price'] * $firstConfig['quantity']) + ($secondConfig['price'] * $secondConfig['quantity'])), ShoppingCart::getTotalCost());
        $this->assertEquals(($firstConfig['quantity']) + ($secondConfig['quantity']), ShoppingCart::getTotalProducts());
    }
}

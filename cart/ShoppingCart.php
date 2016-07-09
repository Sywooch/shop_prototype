<?php

namespace app\cart;

use yii\base\Object;
use yii\base\ErrorException;
use app\traits\ExceptionsTrait;
use app\helpers\SessionHelper;
use app\models\ProductsModel;
use app\models\UsersModel;

/**
 * Предоставляет свойства и методы для работы с корзиной покупок
 */
class ShoppingCart extends Object
{
    use ExceptionsTrait;
    
    /**
     * @var array массив объектов, описывающих выбранные продукты
     * название, артикул, стоимость, кол-во, цвет, размер и т.д.
     */
    private $_productsArray = array();
    /**
     * @var float общая сумма покупок
     */
    private $_totalCost = 0.00;
    /**
     * @var int общее кол-во товаров в корзине
     */
    private $_totalProducts = 0;
    /**
     * @var object объект пользователя, связанного с заказами в корзине
     */
    public $user = NULL;
    
    /**
     * Добавляет продукт в массив выбранных к покупке
     * @param object $object объект модели, представляющий продукт
     * @return boolean
     */
    public function addProduct(ProductsModel $object)
    {
        try {
            if (!$this->getHash($object)) {
                throw new ErrorException('Не удалось создать хэш для объекта!');
            }
            foreach ($this->_productsArray as $objectInArray) {
                if ($objectInArray->hash == $object->hash) {
                    $objectInArray->quantity++;
                    return true;
                }
            }
            $this->_productsArray[] = $object;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет продукт из массива выбранных к покупке
     * @param object $object объект модели, представляющий продукт
     * @return boolean
     */
    public function removeProduct(ProductsModel $object)
    {
        try {
            $result = array();
            foreach ($this->_productsArray as $product) {
                if ($product->hash != $object->hash) {
                    $result[] = $product;
                }
            }
            $this->_productsArray = $result;
            
            if (empty($this->_productsArray)) {
                if (!$this->clearProductsArray()) {
                    throw new ErrorException('Не удалось очистить корзину!');
                }
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет данные продукта из массива выбранных к покупке
     * @param object $object объект модели, представляющий продукт
     * @return boolean
     */
    public function updateProduct(ProductsModel $object)
    {
        try {
            foreach ($this->_productsArray as $objectInArray) {
                if ($objectInArray->hash == $object->hash) {
                    foreach ($object as $key=>$value) {
                        if (!empty($value)) {
                            $objectInArray->$key = $value;
                        }
                    }
                    if (!$this->getHash($objectInArray)) {
                        throw new ErrorException('Не удалось создать хэш для объекта!');
                    }
                    break;
                }
            }
            $prevObject;
            $prevHash = '';
            foreach ($this->_productsArray as $key=>$objectInArray) {
                if ($objectInArray->hash == $prevHash) {
                    $prevObject->quantity += $objectInArray->quantity;
                    unset($this->_productsArray[$key]);
                }
                $prevObject = $objectInArray;
                $prevHash = $objectInArray->hash;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значения $this->_productsArray
     * @return array
     */
    public function getProductsArray()
    {
        try {
            return $this->_productsArray;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает восстановленный из сесии массив объектов свойству $this->_productsArray
     * @param array $productsArray массив объектов модели, представляющей продукт
     * @return boolean
     */
    public function setProductsArray(Array $productsArray)
    {
        try {
            $this->_productsArray = $productsArray;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Очищает массив объектов $this->_productsArray, удаляя все товары из корзины
     * @return boolean
     */
    public function clearProductsArray()
    {
        try {
            if (empty(\Yii::$app->params['cartKeyInSession'])) {
                throw new ErrorException('Не установлена переменная cartKeyInSession!');
            }
            if (empty(\Yii::$app->params['usersKeyInSession'])) {
                throw new ErrorException('Не установлена переменная usersKeyInSession!');
            }
            
            $this->_productsArray = array();
            $this->user = NULL;
            $cleanResult = SessionHelper::removeVarFromSession([
                \Yii::$app->params['cartKeyInSession'],
                \Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.name',
                \Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.surname',
                \Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.emails.email',
                \Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.phones.phone',
                \Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.address',
                \Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.city',
                \Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.postcode',
                \Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.country',
                \Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.deliveries.id',
                \Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.payments.id',
            ]);
            if (!$cleanResult) {
                throw new ErrorException('Ошибка при удалении переменной из сесии!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Заполняет свойства класса краткими данными о товарах в корзине
     * @return boolean
     */
    public function getShortData()
    {
        try {
            if (!empty($this->_productsArray)) {
                $this->_totalCost = 0.00;
                $this->_totalProducts = 0;
                foreach ($this->_productsArray as $objectInArray) {
                    $this->_totalCost += ($objectInArray->price * $objectInArray->quantity);
                    $this->_totalProducts += $objectInArray->quantity;
                }
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение $this->_totalCost
     * @return int
     */
    public function getTotalCost()
    {
        try {
            return $this->_totalCost;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение $this->t_totalProducts
     * @return int
     */
    public function getTotalProducts()
    {
        try {
            return $this->_totalProducts;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает md5 hash из значений свойств, однозначно идентифицируя объект
     * $safeProperties - массив свойств, га основании которых создается хэш
     * @param object объект для которого необходимо создать хэш
     * @return boolean
     */
    private function getHash($object)
    {
        try {
            $safeProperties = ['id', 'code', 'colorToCart', 'sizeToCart'];
            $stringToHash = '';
            foreach ($object as $property=>$value) {
                if (in_array($property, $safeProperties)) {
                    $stringToHash .= $value;
                }
            }
            if (!$object->hash = md5($stringToHash)) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

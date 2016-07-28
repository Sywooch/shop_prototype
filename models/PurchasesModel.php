<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\AbstractBaseModel;
use app\helpers\MappersHelper;
use app\models\{ProductsModel, 
    ColorsModel, 
    DeliveriesModel,
    PaymentsModel};

/**
 * Представляет данные таблицы users
 */
class PurchasesModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    /**
     * Сценарий сохранения данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    
    public $id = '';
    public $id_users = '';
    public $id_products = '';
    public $quantity = '';
    public $id_colors = '';
    public $id_sizes = '';
    public $id_deliveries = '';
    public $id_payments = '';
    
    private $_received = 0;
    private $_received_date = null;
    private $_processed = 0;
    private $_canceled = 0;
    private $_shipped = 0;
    
    /**
     * @var object экземпляр ProductsModel, представляющий продукт, 
     * связанный с текущим экзкмпляром PurchasesModel
     * @see getProductObject()
     */
    private $_productsObject = null;
    /**
     * @var object экземпляр ColorsModel, представляющий color, 
     * связанный с текущим экзкмпляром PurchasesModel
     * @see getColorsObject()
     */
    private $_colorsObject = null;
    /**
     * @var object экземпляр SizesModel, представляющий size, 
     * связанный с текущим экзкмпляром PurchasesModel
     * @see getSizesObject()
     */
    private $_sizesObject = null;
    /**
     * @var object экземпляр DeliveriesModel, представляющий delivery, 
     * связанный с текущим экзкмпляром PurchasesModel
     * @see getDeliveriesObject()
     */
    private $_deliveriesObject = null;
    /**
     * @var object экземпляр PaymentsModel, представляющий payment, 
     * связанный с текущим экзкмпляром PurchasesModel
     * @see getPaymentsObject()
     */
    private $_paymentsObject = null;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['id_users', 'id_products', 'id_colors', 'id_sizes', 'quantity', 'id_deliveries', 'id_payments'],
            self::GET_FROM_DB=>['id', 'id_users', 'id_products', 'id_colors', 'id_sizes', 'quantity', 'id_deliveries', 'id_payments', 'received', 'received_date', 'processed', 'canceled', 'shipped'],
        ];
    }
    
    /**
     * Присваивает значение свойству $this->_received
     * @param string $value значение received
     * @return boolean
     */
    public function setReceived($value)
    {
        try {
            if ($value) {
                $this->_received = 1;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_received
     * @return int
     */
    public function getReceived()
    {
        try {
            return $this->_received;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_received_date
     * @param string $value значение received_date
     * @return boolean
     */
    public function setReceived_date($value)
    {
        try {
            $this->_received_date = $value;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_received_date
     * @return int
     */
    public function getReceived_date()
    {
        try {
            if (is_null($this->_received_date)) {
                $this->_received_date = time();
            }
            return $this->_received_date;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_processed
     * @param string $value значение processed
     * @return boolean
     */
    public function setProcessed($value)
    {
        try {
            if ($value) {
                $this->_processed = 1;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_processed
     * @return int
     */
    public function getProcessed()
    {
        try {
            return $this->_processed;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_canceled
     * @param string $value значение canceled
     * @return boolean
     */
    public function setCanceled($value)
    {
        try {
            if ($value) {
                $this->_canceled = 1;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_canceled
     * @return int
     */
    public function getCanceled()
    {
        try {
            return $this->_canceled;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_shipped
     * @param string $value значение shipped
     * @return boolean
     */
    public function setShipped($value)
    {
        try {
            if ($value) {
                $this->_shipped = 1;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_shipped
     * @return int
     */
    public function getShipped()
    {
        try {
            return $this->_shipped;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект ProductsModel, представляющий продукт, 
     * связанный с текущим экзкмпляром PurchasesModel
     * @return object ProductsModel
     */
    public function getProductsObject()
    {
        try {
            if (is_null($this->_productsObject)) {
                if (!empty($this->id_products)) {
                    $this->_productsObject = MappersHelper::getProductsById(new ProductsModel(['id'=>$this->id_products]));
                }
            }
            return $this->_productsObject;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект ColorsModel, представляющий color, 
     * связанный с текущим экзкмпляром PurchasesModel
     * @return object ColorsModel
     */
    public function getColorsObject()
    {
        try {
            if (is_null($this->_colorsObject)) {
                if (!empty($this->id_colors)) {
                    $this->_colorsObject = MappersHelper::getColorsById(new ColorsModel(['id'=>$this->id_colors]));
                }
            }
            return $this->_colorsObject;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект SizesModel, представляющий size, 
     * связанный с текущим экзкмпляром PurchasesModel
     * @return object SizesModel
     */
    public function getSizesObject()
    {
        try {
            if (is_null($this->_sizesObject)) {
                if (!empty($this->id_sizes)) {
                    $this->_sizesObject = MappersHelper::getSizesById(new SizesModel(['id'=>$this->id_sizes]));
                }
            }
            return $this->_sizesObject;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект DeliveriesModel, представляющий delivery, 
     * связанный с текущим экзкмпляром PurchasesModel
     * @return object DeliveriesModel
     */
    public function getDeliveriesObject()
    {
        try {
            if (is_null($this->_deliveriesObject)) {
                if (!empty($this->id_deliveries)) {
                    $this->_deliveriesObject = MappersHelper::getDeliveriesById(new DeliveriesModel(['id'=>$this->id_deliveries]));
                }
            }
            return $this->_deliveriesObject;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект PaymentsModel, представляющий payment, 
     * связанный с текущим экзкмпляром PurchasesModel
     * @return object PaymentsModel
     */
    public function getPaymentsObject()
    {
        try {
            if (is_null($this->_paymentsObject)) {
                if (!empty($this->id_payments)) {
                    $this->_paymentsObject = MappersHelper::getPaymentsById(new PaymentsModel(['id'=>$this->id_payments]));
                }
            }
            return $this->_paymentsObject;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает текущий статус обработки заказа
     * @return string
     */
    public function getDeliveryStatus()
    {
        try {
            if (empty(\Yii::$app->params['deliveryStatusesArray'])) {
                throw new ErrorException('Массив сообщений о текущем статусе доставки пуст!');
            }
            if (empty(\Yii::$app->params['deliveryStatusesArray']['shipped']) || empty(\Yii::$app->params['deliveryStatusesArray']['canceled']) || empty(\Yii::$app->params['deliveryStatusesArray']['processed']) || empty(\Yii::$app->params['deliveryStatusesArray']['received'])) {
                throw new ErrorException('Ошибка при получении сообщения о текущем статусе доставки!');
            }
            if (!empty($this->shipped)) {
                return \Yii::$app->params['deliveryStatusesArray']['shipped'];
            } elseif (!empty($this->canceled)) {
                return \Yii::$app->params['deliveryStatusesArray']['canceled'];
            } elseif (!empty($this->processed)) {
                return \Yii::$app->params['deliveryStatusesArray']['processed'];
            }
            return \Yii::$app->params['deliveryStatusesArray']['received'];
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

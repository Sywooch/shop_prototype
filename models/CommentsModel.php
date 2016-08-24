<?php

namespace app\models;

use yii\base\ErrorException;
use yii\db\Transaction;
use app\models\{AbstractBaseModel,
    EmailsModel,
    ProductsModel};
use app\helpers\MappersHelper;

/**
 * Представляет данные таблицы currency
 */
class CommentsModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    /**
     * Сценарий cut загрузки данных из формы для обновления товара
    */
    const GET_FOR_UPDATE_CUT = 'getForUpdateCut';
    
    public $id;
    public $text;
    public $name;
    public $id_emails;
    public $id_products;
    public $active;
    
    private $_date = null;
    
    /**
     * @var object экземпляр EmailModel, связанный с текущим комментарием
     */
    private $_emails;
    /**
     * @var object экземпляр ProductsModel, связанный с текущим комментарием
     */
    private $_products;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['text', 'name', 'active'],
            self::GET_FROM_DB=>['id', 'date', 'text', 'name', 'id_emails', 'id_products', 'active'],
            self::GET_FOR_UPDATE_CUT=>['id', 'active'],
        ];
    }
    
    public function rules()
    {
        return [
            [['text', 'name'], 'required', 'on'=>self::GET_FROM_FORM],
            [['text', 'name'], 'app\validators\StripTagsValidator', 'on'=>self::GET_FROM_FORM],
            [['id', 'active'], 'required', 'on'=>self::GET_FOR_UPDATE_CUT],
        ];
    }
    
    /**
     * Присваивает значение свойству $this->_emails
     * @param object $emailsModel объект EmailsModel
     * @return boolean EmailsModel
     */
    public function setEmails(EmailsModel $emailsModel)
    {
        try {
            $this->_emails = $emailsModel;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Получает значение свойства $this->_emails
     * @return object
     */
    public function getEmails()
    {
        try {
            if (is_null($this->_emails)) {
                if (!empty($this->id_emails)) {
                    $this->_emails = MappersHelper::getEmailsById(new EmailsModel(['id'=>$this->id_emails]));
                }
            }
            return $this->_emails;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_products
     * @param object $productsModel объект ProductsModel
     * @return boolean
     */
    public function setProducts(ProductsModel $productsModel)
    {
        try {
            $this->_products = $productsModel;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Получает значение свойства $this->_products
     * @return object ProductsModel
     */
    public function getProducts()
    {
        try {
            if (is_null($this->_products)) {
                if (!empty($this->id_products)) {
                    $this->_products = MappersHelper::getProductsById(new ProductsModel(['id'=>$this->id_products]));
                }
            }
            return $this->_products;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_date
     * @param string $value
     * @return boolean
     */
    public function setDate($value)
    {
        try {
            if (is_numeric($value)) {
                $this->_date = $value;
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Получает значение свойства $this->_date
     * @return UNIX Timestamp
     */
    public function getDate()
    {
        try {
            if (is_null($this->_date)) {
                $this->_date = time();
            }
            return $this->_date;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

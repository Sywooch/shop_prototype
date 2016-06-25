<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\AbstractBaseModel;
use app\models\EmailsModel;
use app\models\AddressModel;
use app\models\PhonesModel;
use app\models\DeliveriesModel;
use app\models\PaymentsModel;
use app\mappers\RulesMapper;
use app\mappers\UsersByLoginMapper;
use app\helpers\TransliterationHelper;
use app\helpers\PasswordHelper;

/**
 * Представляет данные таблицы users
 */
class UsersModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы регистрации
    */
    const GET_FROM_FORM = 'getFromForm';
    /**
     * Сценарий сохранения данных из формы заказа
    */
    const GET_FROM_CART_FORM = 'getFromCartForm';
    /**
     * Сценарий сохранения данных из БД
    */
    const GET_FROM_DB = 'getFromDB';
    
    public $name;
    public $surname;
    public $id_emails;
    public $id_phones;
    public $id_address;
    
    /**
     * @var array массив ID rules, выбранных пользователем в форме
     */
    public $rulesFromForm = array();
    
    private $_login = NULL;
    private $_id = NULL;
    private $_password;
    private $_allRules = NULL;
    private $_emails = NULL;
    private $_address = NULL;
    private $_phones = NULL;
    private $_deliveries = NULL;
    private $_payments = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['login', 'password', 'name', 'surname', 'rulesFromForm'],
            self::GET_FROM_DB=>['id', 'login', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
            self::GET_FROM_CART_FORM=>['name', 'surname'],
        ];
    }
    
    public function rules()
    {
        return [
            [['login', 'password', 'rulesFromForm'], 'required', 'on'=>self::GET_FROM_FORM],
            ['login', 'app\validators\ExistUserValidator', 'on'=>self::GET_FROM_FORM],
            [['name', 'surname'], 'required', 'on'=>self::GET_FROM_CART_FORM],
        ];
    }
    
    /**
     * Хэширует пароль перед присвоением значения свойству $this->_password
     * @param string $value значение пароля
     */
    public function setPassword($value)
    {
        try {
            $this->_password = password_hash($value, PASSWORD_DEFAULT);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_password
     */
    public function getPassword()
    {
        try {
            if (is_null($this->_password)) {
                if (isset($this->name)) {
                    $this->_password = PasswordHelper::getPassword();
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_password;
    }
    
    /**
     * Возвращает массив объектов всех доступных rules для формы создания пользователя
     * @return array
     */
    public function getAllRules()
    {
        try {
            if (is_null($this->_allRules)) {
                $rulesMapper = new RulesMapper([
                    'tableName'=>'rules',
                    'fields'=>['id', 'rule'],
                    'orderByField'=>'rule',
                ]);
                $this->_allRules = $rulesMapper->getGroup();
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_allRules;
    }
    
    /**
     * Возвращает значение свойства $this->_id
     */
    public function getId()
    {
        try {
            if (is_null($this->_id)) {
                if (isset($this->login)) {
                    $usersByLoginMapper = new UsersByLoginMapper([
                        'tableName'=>'users',
                        'fields'=>['id'],
                        'model'=>$this,
                    ]);
                    if ($objectUser = $usersByLoginMapper->getOneFromGroup()) {
                        $this->_id = $objectUser->id;
                    }
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_id;
    }
    
    /**
     * Присваивает значение свойству $this->_id
     * @param string $value значение ID
     */
    public function setId($value)
    {
        $this->_id = $value;
    }
    
    /**
     * Возвращает значение свойства $this->_login
     */
    public function getLogin()
    {
        try {
            if (is_null($this->_login)) {
                if (isset($this->name)) {
                    $login = TransliterationHelper::getTransliteration($this->name);
                    $this->_login = $login . substr(md5($login . time()), 0, 5);
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_login;
    }
    
    /**
     * Присваивает значение свойству $this->_login
     * @param string $value значение login
     */
    public function setLogin($value)
    {
        $this->_login = $value;
    }
    
    /**
     * Возвращает значение свойства $this->_emails
     */
    public function getEmails()
    {
        return $this->_emails;
    }
    
    /**
     * Присваивает значение свойству $this->_emails
     * @param string $value значение email
     */
    public function setEmails(EmailsModel $value)
    {
        $this->_emails = $value;
    }
    
    /**
     * Возвращает значение свойства $this->_address
     */
    public function getAddress()
    {
        return $this->_address;
    }
    
    /**
     * Присваивает значение свойству $this->_address
     * @param string $value значение address
     */
    public function setAddress(AddressModel $value)
    {
        $this->_address = $value;
    }
    
    /**
     * Возвращает значение свойства $this->_phones
     */
    public function getPhones()
    {
        return $this->_phones;
    }
    
    /**
     * Присваивает значение свойству $this->_phones
     * @param string $value значение phone
     */
    public function setPhones(PhonesModel $value)
    {
        $this->_phones = $value;
    }
    
    /**
     * Возвращает значение свойства $this->_deliveries
     */
    public function getDeliveries()
    {
        return $this->_deliveries;
    }
    
    /**
     * Присваивает значение свойству $this->_deliveries
     * @param string $value значение delivery
     */
    public function setDeliveries(DeliveriesModel $value)
    {
        $this->_deliveries = $value;
    }
    
    /**
     * Возвращает значение свойства $this->_payments
     */
    public function getPayments()
    {
        return $this->_payments;
    }
    
    /**
     * Присваивает значение свойству $this->_payments
     * @param string $value значение payments
     */
    public function setPayments(PaymentsModel $value)
    {
        $this->_payments = $value;
    }
}

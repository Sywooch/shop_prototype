<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\AbstractBaseModel;
use app\models\EmailsModel;
use app\models\AddressModel;
use app\models\PhonesModel;
use app\models\DeliveriesModel;
use app\models\PaymentsModel;
use app\models\CurrencyModel;
use app\mappers\RulesMapper;
use app\mappers\UsersByLoginMapper;
use app\mappers\EmailsByIdMapper;
use app\mappers\PhonesByIdMapper;
use app\mappers\AddressByIdMapper;
use app\mappers\CurrencyByMainMapper;
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
    const GET_FROM_REGISTRATION_FORM = 'getFromForm';
    /**
     * Сценарий сохранения данных из формы авторизации
    */
    const GET_FROM_LOGIN_FORM = 'getFromLoginForm';
    /**
     * Сценарий выхода из аккаунта
    */
    const GET_FROM_LOGOUT_FORM = 'getFromLogoutForm';
    /**
     * Сценарий сохранения данных из формы заказа
    */
    const GET_FROM_CART_FORM = 'getFromCartForm';
    /**
     * Сценарий сохранения данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    
    public $name = '';
    public $surname = '';
    public $id_emails = 0;
    public $id_phones = 0;
    public $id_address = 0;
    
    /**
     * @var string хранить пароль в незахэшированном виде
     */
    public $rawPassword = '';
    
    /**
     * @var array массив ID rules, выбранных пользователем в форме
     */
    private $_rulesFromForm = array();
    
    /**
     * @var object объект валюты, назначенные по умолчанию или добавленный при авторизации из сессии
     */
    private $_currency = NULL;
    
    private $_login = NULL;
    private $_id = NULL;
    private $_password = NULL;
    private $_allRules = NULL;
    private $_emails = NULL;
    private $_address = NULL;
    private $_phones = NULL;
    private $_deliveries = NULL;
    private $_payments = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_REGISTRATION_FORM=>['login', 'rawPassword'],
            self::GET_FROM_DB=>['id', 'login', 'password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
            self::GET_FROM_CART_FORM=>['name', 'surname'],
            self::GET_FROM_LOGIN_FORM=>['login', 'rawPassword'],
            self::GET_FROM_LOGOUT_FORM=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['login', 'rawPassword'], 'required', 'on'=>self::GET_FROM_REGISTRATION_FORM],
            [['name', 'surname'], 'required', 'on'=>self::GET_FROM_CART_FORM],
            [['login', 'rawPassword'], 'required', 'on'=>self::GET_FROM_LOGIN_FORM],
            ['login', 'app\validators\LoginExistsValidator'],
            ['rawPassword', 'app\validators\PasswordExistsValidator', 'on'=>self::GET_FROM_LOGIN_FORM, 'when'=>function($model) {
                return empty($model->errors) ? true : false;
            }],
        ];
    }
    
    /**
     * Присваивает значение свойству $this->_password
     * @param string $value значение пароля
     * @return boolean
     */
    public function setPassword($value)
    {
        try {
            $this->_password = $value;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_password
     * @return string
     */
    public function getPassword()
    {
        try {
            if (is_null($this->_password) && \Yii::$app->user->login == \Yii::$app->params['nonAuthenticatedUserLogin']) {
                if (empty($this->rawPassword)) {
                    $this->rawPassword = PasswordHelper::getPassword();
                    if (!is_string($this->rawPassword)) {
                        return NULL;
                    }
                }
                if (!$this->_password = password_hash($this->rawPassword, PASSWORD_DEFAULT)) {
                    return NULL;
                }
            }
            return $this->_password;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
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
                $rulesArray = $rulesMapper->getGroup();
                if (!is_array($rulesArray) || empty($rulesArray)) {
                    return NULL;
                }
                $this->_allRules = $rulesArray;
            }
            return $this->_allRules;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_id
     * @param string/int $value значение ID
     * @return boolean
     */
    public function setId($value)
    {
        try {
            if (is_numeric($value) || is_null($value)) {
                $this->_id = $value;
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_id
     * @return int
     */
    public function getId()
    {
        try {
            if (is_null($this->_id)) {
                if (!empty($this->login)) {
                    $usersByLoginMapper = new UsersByLoginMapper([
                        'tableName'=>'users',
                        'fields'=>['id'],
                        'model'=>$this,
                    ]);
                    $objectUser = $usersByLoginMapper->getOneFromGroup();
                    if (!is_object($objectUser) || !$objectUser instanceof $this) {
                        return NULL;
                    }
                    $this->_id = $objectUser->id;
                }
            }
            return $this->_id;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_login
     * @param string $value значение login
     * @return boolean
     */
    public function setLogin($value)
    {
        try {
            $this->_login = $value;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_login
     * @return string
     */
    public function getLogin()
    {
        try {
            if (is_null($this->_login) && \Yii::$app->user->login == \Yii::$app->params['nonAuthenticatedUserLogin']) {
                if (!empty($this->name) && $this->scenario == self::GET_FROM_CART_FORM) {
                    $login = TransliterationHelper::getTransliteration($this->name);
                    if (!is_string($login)) {
                        return NULL;
                    }
                    $this->_login = $login . substr(md5($login . time()), 0, 5);
                }
            }
            return $this->_login;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_emails
     * @param object $emailsModel EmailsModel
     * @return boolean
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
     * Возвращает значение свойства $this->_emails
     * @return object EmailsModel
     */
    public function getEmails()
    {
        try {
            if (is_null($this->_emails)) {
                if (!empty($this->id_emails)) {
                    $emailsByIdMapper = new EmailsByIdMapper([
                        'tableName'=>'emails',
                        'fields'=>['id', 'email'],
                        'model'=>new EmailsModel([
                            'id'=>$this->id_emails,
                        ]),
                    ]);
                    $emailsModel = $emailsByIdMapper->getOneFromGroup();
                    if (!is_object($emailsModel) || !$emailsModel instanceof EmailsModel) {
                        return NULL;
                    }
                    $this->_emails = $emailsModel;
                }
            }
            return $this->_emails;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_address
     * @param object $addressModel AddressModel
     * @return boolean
     */
    public function setAddress(AddressModel $addressModel)
    {
        try {
            $this->_address = $addressModel;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_address
     * @return object AddressModel
     */
    public function getAddress()
    {
         try {
            if (is_null($this->_address)) {
                if (!empty($this->id_address)) {
                    $addressByIdMapper = new AddressByIdMapper([
                        'tableName'=>'address',
                        'fields'=>['id', 'address', 'city', 'country', 'postcode'],
                        'model'=>new AddressModel([
                            'id'=>$this->id_address,
                        ]),
                    ]);
                    $addressModel = $addressByIdMapper->getOneFromGroup();
                    if (!is_object($addressModel) || !$addressModel instanceof AddressModel) {
                        return NULL;
                    }
                    $this->_address = $addressModel;
                }
            }
            return $this->_address;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_phones
     * @param object $phonesModel PhonesModel
     * @return boolean
     */
    public function setPhones(PhonesModel $phonesModel)
    {
        try {
            $this->_phones = $phonesModel;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_phones
     * @return object PhonesModel
     */
    public function getPhones()
    {
        try {
            if (is_null($this->_phones)) {
                if (!empty($this->id_phones)) {
                    $phonesByIdMapper = new PhonesByIdMapper([
                        'tableName'=>'phones',
                        'fields'=>['id', 'phone'],
                        'model'=>new PhonesModel([
                            'id'=>$this->id_phones,
                        ]),
                    ]);
                    $phonesModel = $phonesByIdMapper->getOneFromGroup();
                    if (!is_object($phonesModel) || !$phonesModel instanceof PhonesModel) {
                        return NULL;
                    }
                    $this->_phones = $phonesModel;
                }
            }
            return $this->_phones;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_deliveries
     * @param object $deliveriesModel DeliveriesModel
     * @return boolean
     */
    public function setDeliveries(DeliveriesModel $deliveriesModel)
    {
        try {
            $this->_deliveries = $deliveriesModel;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_deliveries
     * @return object DeliveriesModel
     */
    public function getDeliveries()
    {
        return $this->_deliveries;
    }
    
    /**
     * Присваивает значение свойству $this->_payments
     * @param object $paymentsModel PaymentsModel
     * @return boolean
     */
    public function setPayments(PaymentsModel $paymentsModel)
    {
        try {
            $this->_payments = $paymentsModel;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_payments
     * @return object PaymentsModel
     */
    public function getPayments()
    {
        return $this->_payments;
    }
    
    /**
     * Присваивает значение свойству $this->_rulesFromForm
     * @param array $value
     * @return boolean
     */
    public function setRulesFromForm(Array $value)
    {
        try {
            $this->_rulesFromForm = $value;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_rulesFromForm
     * @return array
     */
    public function getRulesFromForm()
    {
        try {
            if (empty($this->_rulesFromForm)) {
                if (empty(\Yii::$app->params['defaultRulesId'])) {
                    throw new ErrorException('Отсутствует значение defaultRulesId!');
                }
                $this->_rulesFromForm = \Yii::$app->params['defaultRulesId'];
            }
            return $this->_rulesFromForm;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных для сохранения в сессии
     * @return array
     */
    public function getDataForSession()
    {
        try {
            return ['id'=>$this->id, 'login'=>$this->login, 'name'=>$this->name, 'surname'=>$this->surname, 'id_emails'=>$this->id_emails, 'id_phones'=>$this->id_phones, 'id_address'=>$this->id_address];
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных для сравнения двух моделей
     * @return array
     */
    public function getDataForСomparison()
    {
        try {
            return ['name'=>$this->name, 'surname'=>$this->surname, 'id_emails'=>$this->id_emails, 'id_phones'=>$this->id_phones, 'id_address'=>$this->id_address];
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_currency
     * @param object $currencyModel CurrencyModel
     * @return boolean
     */
    public function setCurrency(CurrencyModel $currencyModel)
    {
        try {
            $this->_currency = $currencyModel;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_currency
     * @return object CurrencyModel
     */
    public function getCurrency()
    {
        try {
            if (is_null($this->_currency)) {
                $currencyByMainMapper = new CurrencyByMainMapper([
                    'tableName'=>'currency',
                    'fields'=>['id', 'currency', 'exchange_rate', 'main'],
                ]);
                $currencyModel = $currencyByMainMapper->getOneFromGroup();
                if (!is_object($currencyModel) || !$currencyModel instanceof CurrencyModel) {
                    return NULL;
                }
                $this->_currency = $currencyModel;
            }
            return $this->_currency;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

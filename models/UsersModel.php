<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractBaseModel,
    EmailsModel,
    AddressModel,
    PhonesModel,
    DeliveriesModel,
    PaymentsModel,
    CurrencyModel};
use app\helpers\{TransliterationHelper,
    PasswordHelper,
    MappersHelper};

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
     * Сценарий сохранения данных из формы обновления данных
    */
    const GET_FROM_UPDATE_FORM = 'getFromUpdateForm';
    /**
     * Сценарий сохранения данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    
    public $name;
    public $surname;
    public $id_emails = 0;
    public $id_phones = 0;
    public $id_address = 0;
    
    /**
     * @var string пароль в незахэшированном виде
     */
    public $rawPassword;
    /**
     * @var string текущий пароль в незахэшированном виде, при смене пароля
     */
    public $currentRawPassword;
    
    /**
     * @var array массив ID rules, выбранных пользователем в форме
     */
    private $_rulesFromForm = array();
    
    /**
     * @var object объект валюты, назначенные по умолчанию или добавленный при авторизации из сессии
     */
    private $_currency = null;
    
    private $_id = null;
    private $_password = null;
    private $_allRules = null;
    private $_emails = null;
    private $_address = null;
    private $_phones = null;
    private $_deliveries = null;
    private $_payments = null;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_REGISTRATION_FORM=>['rawPassword'], 
            self::GET_FROM_DB=>['id', 'password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'], 
            self::GET_FROM_CART_FORM=>['name', 'surname'],
            self::GET_FROM_LOGIN_FORM=>['rawPassword'], 
            self::GET_FROM_LOGOUT_FORM=>['id'],
            self::GET_FROM_UPDATE_FORM=>['id', 'name', 'surname', 'currentRawPassword', 'rawPassword'], 
        ];
    }
    
    public function rules()
    {
        return [
            [['rawPassword'], 'required', 'on'=>self::GET_FROM_REGISTRATION_FORM], 
            [['rawPassword'], 'app\validators\StripTagsValidator', 'on'=>self::GET_FROM_REGISTRATION_FORM], 
            [['name', 'surname'], 'required', 'on'=>self::GET_FROM_CART_FORM],
            [['name', 'surname'], 'app\validators\StripTagsValidator', 'on'=>self::GET_FROM_CART_FORM],
            [['rawPassword'], 'required', 'on'=>self::GET_FROM_LOGIN_FORM], 
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
            if (is_null($this->_password)) { 
                if (empty($this->rawPassword)) {
                    $this->rawPassword = PasswordHelper::getPassword();
                    if (!is_string($this->rawPassword)) {
                        return null;
                    }
                }
                if (!$this->_password = password_hash($this->rawPassword, PASSWORD_DEFAULT)) {
                    return null;
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
                $this->_allRules =MappersHelper::getRulesList();
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
                if (!empty($this->id_emails)) {
                    $objectUser = MappersHelper::getUsersByIdEmails($this);
                    if (!is_object($objectUser) || !$objectUser instanceof $this) {
                        return null;
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
                    $this->_emails = MappersHelper::getEmailsById(new EmailsModel(['id'=>$this->id_emails]));
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
                    $this->_address = MappersHelper::getAddressById(new AddressModel(['id'=>$this->id_address]));
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
                    $this->_phones = MappersHelper::getPhonesById(new PhonesModel(['id'=>$this->id_phones]));
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
     * Возвращает массив данных, представляюших значения свойств
     * @return array
     */
    public function getDataArray()
    {
        try {
            return ['id'=>$this->id, 'id_emails'=>$this->id_emails, 'name'=>$this->name, 'surname'=>$this->surname, 'id_phones'=>$this->id_phones, 'id_address'=>$this->id_address]; 
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
            return ['id_emails'=>$this->id_emails, 'name'=>$this->name, 'surname'=>$this->surname, 'id_phones'=>$this->id_phones, 'id_address'=>$this->id_address];
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
                $this->_currency = MappersHelper::getCurrencyByMain();
            }
            return $this->_currency;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

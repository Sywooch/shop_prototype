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
use app\mappers\EmailsByIdMapper;
use app\mappers\PhonesByIdMapper;
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
            if (is_null($this->_password)) {
                if (empty($this->rawPassword)) {
                    $this->rawPassword = PasswordHelper::getPassword();
                    if (!is_string($this->rawPassword)) {
                        throw new ErrorException('Неверный формат данных!');
                    }
                }
                if (!$this->_password = password_hash($this->rawPassword, PASSWORD_DEFAULT)) {
                    throw new ErrorException('Ошибка при хэшировании пароля!');
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
                    return false;
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
            if (is_null($this->_login)) {
                if (!empty($this->name) && $this->scenario == self::GET_FROM_CART_FORM) {
                    $login = TransliterationHelper::getTransliteration($this->name);
                    if (!is_string($login)) {
                        throw new ErrorException('Неверный формат данных!');
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
     * @param object $value
     * @return boolean
     */
    public function setEmails(EmailsModel $value)
    {
        try {
            $this->_emails = $value;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_emails
     * @return object
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
     * @param object $value
     * @return boolean
     */
    public function setAddress(AddressModel $value)
    {
        try {
            $this->_address = $value;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_address
     * @return object
     */
    public function getAddress()
    {
        return $this->_address;
    }
    
    /**
     * Присваивает значение свойству $this->_phones
     * @param string $value значение phone
     * @return boolean
     */
    public function setPhones(PhonesModel $value)
    {
        try {
            $this->_phones = $value;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_phones
     * @return object
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
     * @param string $value значение delivery
     * @return boolean
     */
    public function setDeliveries(DeliveriesModel $value)
    {
        try {
            $this->_deliveries = $value;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_deliveries
     * @return object
     */
    public function getDeliveries()
    {
        return $this->_deliveries;
    }
    
    /**
     * Присваивает значение свойству $this->_payments
     * @param string $value значение payments
     * @return boolean
     */
    public function setPayments(PaymentsModel $value)
    {
        try {
            $this->_payments = $value;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_payments
     * @return object
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
}

<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\models\EmailsModel;
use yii\base\ErrorException;
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
    
    /**
     * @var array массив ID rules, выбранных пользователем в форме
     */
    public $rulesFromForm = array();
    
    private $_login = NULL;
    private $_id = NULL;
    private $_password;
    private $_allRules = NULL;
    private $_emails = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['login', 'password', 'name', 'surname', 'rulesFromForm'],
            self::GET_FROM_DB=>['id', 'login', 'name', 'surname'],
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
                $this->_password = PasswordHelper::getPassword();
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
                $usersByLoginMapper = new UsersByLoginMapper([
                    'tableName'=>'users',
                    'fields'=>['id'],
                    'model'=>$this,
                ]);
                $objectUser = $usersByLoginMapper->getOneFromGroup();
                $this->_id = $objectUser->id;
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
                if (!isset($this->name) || empty($this->name)) {
                    throw new ErrorException('Не присвоено значение свойству $this->name');
                }
                $this->_login = TransliterationHelper::getTransliteration($this->name);
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
        $this->_emails[] = $value;
    }
}

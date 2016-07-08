<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\mappers\PhonesByPhoneMapper;
use yii\base\ErrorException;

/**
 * Представляет данные таблицы phones
 */
class PhonesModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из формы
     */
    const GET_FROM_FORM = 'getFromForm';
    /**
     * Сценарий загрузки данных из БД
     */
    const GET_FROM_DB = 'getFromDb';
    
    public $phone = '';
    
    private $_id = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['phone'],
            self::GET_FROM_DB=>['id', 'phone'],
        ];
    }
    
    public function rules()
    {
        return [
            [['phone'], 'required', 'on'=>self::GET_FROM_FORM],
        ];
    }
    
     /**
     * Присваивает значение свойству $this->_id
     * @param string $value значение ID
     * @return boolean
     */
    public function setId($value)
    {
        try {
            if (is_numeric($value)) {
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
                if (!empty($this->phone)) {
                    $phonesByPhoneMapper = new PhonesByPhoneMapper([
                        'tableName'=>'phones',
                        'fields'=>['id', 'phone'],
                        'model'=>$this
                    ]);
                    $objectModel = $phonesByPhoneMapper->getOneFromGroup();
                    if (!is_object($objectModel) || !$objectModel instanceof $this) {
                        return NULL;
                    }
                    $this->_id = $objectModel->id;
                }
            }
            return $this->_id;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

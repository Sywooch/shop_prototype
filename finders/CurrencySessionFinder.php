<?php

namespace app\finders;

use yii\base\{ErrorException,
    Model};
use app\finders\FinderInterface;
use app\exceptions\ExceptionsTrait;
use app\helpers\SessionHelper;
use app\models\CurrencyModel;

class CurrencySessionFinder extends Model implements FinderInterface
{
    use ExceptionsTrait;
    
    /**
     * @var string key ключ доступа к данным
     */
    public $key;
    /**
     * @var object Model
     */
    private $entity;
    
    public function rules()
    {
        return [
            [['key'], 'required'],
        ];
    }
    
    /**
     * Загружает данные в свойства модели
     * @param $data массив данных
     * @return bool
     */
    public function load($data, $formName=null)
    {
        try {
            return parent::load($data, '');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные из сессионного хранилища
     * @return Model/null
     */
    public function find()
    {
        try {
            if (empty($this->entity)) {
                if ($this->validate()) {
                    $data = SessionHelper::read($this->key);
                    if (!empty($data)) {
                        $this->entity = new CurrencyModel($data);
                    }
                }
            }
            
            return !empty($this->entity) ? $this->entity : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

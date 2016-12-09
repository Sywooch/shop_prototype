<?php

namespace app\savers;

use yii\base\{ErrorException,
    Model};
use app\savers\AbstractBaseSaver;
use app\helpers\SessionHelper;

/**
 * Сохранаяет данные в сессионном хранилище
 */
class OneSessionSaver extends AbstractBaseSaver
{
    /**
     * @var staring ключ, под которым будут сохранены данные в сессии
     */
    public $key;
    /**
     * @var Model модель, которая содержит данные для записи
     */
    private $model;
    
    public function rules()
    {
        return [
            [['key', 'model'], 'required']
        ];
    }
    
    /**
     * Сохраняет данные в сессионном хранилище
     * @return bool
     */
    public function save()
    {
        try {
            if ($this->validate() === false) {
                throw new ErrorException($this->modelError($this->errors));
            }
            
            SessionHelper::write($this->key, $this->model->toArray());
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model OneSessionSaver::model
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        try {
            $this->model = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает OneSessionSaver::model
     * @return Model
     */
    public function getModel()
    {
        try {
            return $this->model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

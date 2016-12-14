<?php

namespace app\savers;

use yii\base\{ErrorException,
    Model};
use app\savers\AbstractBaseSaver;
use app\helpers\SessionHelper;
use app\validators\ModelsArrayValidator;

/**
 * Сохранаяет данные в сессионном хранилище
 */
class SessionSaver extends AbstractBaseSaver
{
    /**
     * @var staring ключ, под которым будут сохранены данные в сессии
     */
    public $key;
    /**
     * @var array объектов Model
     */
    private $models = [];
    
    public function rules()
    {
        return [
            [['key', 'models'], 'required'],
            [['models'], ModelsArrayValidator::class]
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
            
            if (count($this->models) > 1) {
                $toRecord = [];
                foreach ($this->models as $model) {
                    $toRecord[] = $model->toArray();
                }
            } else {
                $toRecord = $this->models[0]->toArray();
            }
            
            SessionHelper::write($this->key, $toRecord);
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array Model SessionSaver::models
     */
    public function setModels(array $models)
    {
        try {
            $this->models = $models;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные Model SessionSaver::models
     * @return array
     */
    public function getModels(): array
    {
        try {
            return $this->models;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

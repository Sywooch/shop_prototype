<?php

namespace app\savers;

use yii\base\ErrorException;
use app\savers\AbstractBaseSaver;
use app\helpers\SessionHelper;

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
    
    /**
     * Сохраняет данные в сессионном хранилище
     * @return bool
     */
    public function save()
    {
        try {
            if (empty($this->key)) {
                throw new ErrorException($this->emptyError('key'));
            }
            if (empty($this->models)) {
                throw new ErrorException($this->emptyError('models'));
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
}
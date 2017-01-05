<?php

namespace app\savers;

use yii\base\{ErrorException,
    Model};
use app\savers\{AbstractBaseSaver,
    SaverModelInterface};
use app\helpers\SessionHelper;

/**
 * Сохранаяет данные в сессионном хранилище
 */
class SessionModelSaver extends AbstractBaseSaver implements SaverModelInterface
{
    /**
     * @var staring ключ, под которым будут сохранены данные в сессии
     */
    public $key;
    /**
     * @var Model
     */
    private $model = [];
    /**
     * @var bool флаг, указывающий сохранить данные во флеш-сесии
     */
    public $flash = false;
    
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
            if (empty($this->model)) {
                throw new ErrorException($this->emptyError('model'));
            }
            
            if ($this->flash === true) {
                SessionHelper::writeFlash($this->key, $this->model->toArray());
            } else {
                SessionHelper::write($this->key, $this->model->toArray());
            }
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model ModelSaver::model
     */
    public function setModel(Model $model)
    {
        try {
            $this->model = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

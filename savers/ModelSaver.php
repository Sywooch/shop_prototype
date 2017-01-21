<?php

namespace app\savers;

use yii\base\{ErrorException,
    Model};
use app\savers\{AbstractBaseSaver,
    SaverModelInterface};

/**
 * Сохраняет данные EmailsModel в СУБД
 */
class ModelSaver extends AbstractBaseSaver implements SaverModelInterface
{
    /**
     * @var Model
     */
    private $model;
    
    /**
     * Сохраняет данные в СУБД
     * @return bool
     */
    public function save()
    {
        try {
            if (empty($this->model)) {
                throw new ErrorException($this->emptyError('model'));
            }
            
            $result = $this->model->save();
            
            if ($result === false) {
                throw new ErrorException($this->methodError('save'));
            }
            
            return $result;
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

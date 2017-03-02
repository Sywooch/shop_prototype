<?php

namespace app\updaters;

use yii\base\{ErrorException,
    Model};
use app\updaters\{AbstractBaseUpdater,
    UpdaterModelInterface};
use app\models\CurrencyModel;

/**
 * Удаляет данные из СУБД
 */
class CurrencyModelUpdater extends AbstractBaseUpdater implements UpdaterModelInterface
{
   /**
     * @var Model
     */
    private $model;
    
    /**
     * Удаляет данные
     * @return int
     */
    public function update()
    {
        try {
            if (empty($this->model)) {
                throw new ErrorException($this->emptyError('model'));
            }
            
            $result = CurrencyModel::updateAll([
                '[[currency.exchange_rate]]'=>$this->model->exchange_rate,
                '[[currency.update_date]]'=>$this->model->update_date,
            ], '[[currency.id]]=:id', [':id'=>$this->model->id]);
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение CurrencyModelUpdater::models
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
}

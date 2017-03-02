<?php

namespace app\updaters;

use yii\base\ErrorException;
use app\updaters\{AbstractBaseUpdater,
    UpdaterArrayInterface};

/**
 * Сохранаяет данные в СУБД
 */
class CurrencyArrayUpdater extends AbstractBaseUpdater implements UpdaterArrayInterface
{
   /**
     * @var array объекты CurrencyModel
     */
    private $models = [];
    
    /**
     * Сохраняет данные
     * @return int
     */
    public function update()
    {
        try {
            if (empty($this->models)) {
                throw new ErrorException($this->emptyError('models'));
            }
            
            $toRecord = [];
            
            foreach ($this->models as $model) {
                $toRecord[] = [
                    'id'=>$model->id,
                    'code'=>$model->code,
                    'main'=>$model->main,
                    'exchange_rate'=>$model->exchange_rate,
                    'update_date'=>$model->update_date,
                ];
            }
            
            $query = \Yii::$app->db->getQueryBuilder()->batchInsert('{{currency}}', ['[[id]]', '[[code]]', '[[main]]', '[[exchange_rate]]', '[[update_date]]'], $toRecord);
            $query .= ' ON DUPLICATE KEY UPDATE [[code]]=[[code]], [[main]]=VALUES([[main]]), [[exchange_rate]]=VALUES([[exchange_rate]]), [[update_date]]=VALUES([[update_date]])';
            $result = \Yii::$app->db->createCommand($query)->execute();
            
            if ((int) $result !== ((int) count($this->models) * 2)) {
                throw new ErrorException($this->methodError('batchInsert'));
            }
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение CurrencyArrayUpdater::models
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

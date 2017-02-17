<?php

namespace app\savers;

use yii\base\ErrorException;
use app\savers\{AbstractBaseSaver,
    SaverArrayInterface};

/**
 * Сохранаяет данные в СУБД
 */
class ProductsSizesArraySaver extends AbstractBaseSaver implements SaverArrayInterface
{
   /**
     * @var array объекты ProductsSizesModel
     */
    private $models = [];
    
    /**
     * Сохраняет данные
     * @return int
     */
    public function save()
    {
        try {
            if (empty($this->models)) {
                throw new ErrorException($this->emptyError('models'));
            }
            
            $toRecord = [];
            
            foreach ($this->models as $model) {
                $toRecord[] = [
                    'id_product'=>$model->id_product,
                    'id_size'=>$model->id_size,
                ];
            }
            
            $result = \Yii::$app->db->createCommand()->batchInsert('{{products_sizes}}', ['id_product', 'id_size'], $toRecord)->execute();
            
            if ((int) $result !== (int) count($this->models)) {
                throw new ErrorException($this->methodError('batchInsert'));
            }
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array ProductsSizesArraySaver::models
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

<?php

namespace app\savers;

use yii\base\ErrorException;
use app\savers\{AbstractBaseSaver,
    SaverArrayInterface};

/**
 * Сохранаяет данные в СУБД
 */
class EmailsMailingsArraySaver extends AbstractBaseSaver implements SaverArrayInterface
{
   /**
     * @var array объекты EmailsMailingsModel
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
                    'id_email'=>$model->id_email,
                    'id_mailing'=>$model->id_mailing,
                ];
            }
            
            $result = \Yii::$app->db->createCommand()->batchInsert('{{emails_mailings}}', ['id_email', 'id_mailing'], $toRecord)->execute();
            
            if ((int) $result !== (int) count($this->models)) {
                throw new ErrorException($this->methodError('batchInsert'));
            }
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array EmailsMailingsArraySaver::models
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

<?php

namespace app\savers;

use yii\base\ErrorException;
use app\savers\{AbstractBaseSaver,
    SaverArrayInterface};
use app\helpers\SessionHelper;

/**
 * Сохранаяет данные в СУБД
 */
class PurchasesArraySaver extends AbstractBaseSaver implements SaverArrayInterface
{
   /**
     * @var array объекты PurchasesModel
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
                    'id_user'=>$model->id_user,
                    'id_name'=>$model->id_name,
                    'id_surname'=>$model->id_surname,
                    'id_email'=>$model->id_email,
                    'id_phone'=>$model->id_phone,
                    'id_address'=>$model->id_address,
                    'id_city'=>$model->id_city,
                    'id_country'=>$model->id_country,
                    'id_postcode'=>$model->id_postcode,
                    'id_product'=>$model->id_product, 
                    'quantity'=>$model->quantity, 
                    'id_color'=>$model->id_color, 
                    'id_size'=>$model->id_size,
                    'price'=>$model->price, 
                    'id_delivery'=>$model->id_delivery, 
                    'id_payment'=>$model->id_payment, 
                    'received'=>$model->received, 
                    'received_date'=>time(),
                ];
            }
            
            $result = \Yii::$app->db->createCommand()->batchInsert('{{purchases}}', ['id_user', 'id_name', 'id_surname', 'id_email', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode', 'id_product', 'quantity', 'id_color', 'id_size', 'price', 'id_delivery', 'id_payment', 'received', 'received_date'], $toRecord)->execute();
            
            if ((int) $result !== (int) count($this->models)) {
                throw new ErrorException($this->methodError('batchInsert'));
            }
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array PurchasesArraySaver::models
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

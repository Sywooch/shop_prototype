<?php

namespace app\removers;

use yii\base\{ErrorException,
    Model};
use app\removers\{AbstractBaseRemover,
    RemoverModelInterface};
use app\models\EmailsMailingsModel;

/**
 * Удаляет данные из СУБД
 */
class EmailsMailingsModelRemover extends AbstractBaseRemover implements RemoverModelInterface
{
   /**
     * @var EmailsMailingsModel
     */
    private $model;
    
    /**
     * Удаляет данные
     * @return int
     */
    public function remove()
    {
        try {
            if (empty($this->model)) {
                throw new ErrorException($this->emptyError('model'));
            }
            
            $result = EmailsMailingsModel::deleteAll(['and', 
                ['[[emails_mailings.id_email]]'=>$this->model->id_email], 
                ['[[emails_mailings.id_mailing]]'=>$this->model->id_mailing]
            ]);
            
            if ((int) $result !== 1) {
                throw new ErrorException($this->methodError('deleteAll'));
            }
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает EmailsMailingsModel EmailsMailingsModelRemover::models
     * @param $model EmailsMailingsModel
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

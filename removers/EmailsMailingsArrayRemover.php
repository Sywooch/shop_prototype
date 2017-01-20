<?php

namespace app\removers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\removers\{AbstractBaseRemover,
    RemoverArrayInterface};
use app\models\EmailsMailingsModel;

/**
 * Удаляет данные из СУБД
 */
class EmailsMailingsArrayRemover extends AbstractBaseRemover implements RemoverArrayInterface
{
   /**
     * @var array объекты EmailsMailingsModel
     */
    private $models = [];
    
    /**
     * Удаляет данные
     * @return int
     */
    public function remove()
    {
        try {
            if (empty($this->models)) {
                throw new ErrorException($this->emptyError('models'));
            }
            
            $result = EmailsMailingsModel::deleteAll(['and', 
                ['in', '[[emails_mailings.id_email]]', ArrayHelper::getColumn($this->models, 'id_email')], 
                ['in', '[[emails_mailings.id_mailing]]',ArrayHelper::getColumn($this->models, 'id_mailing')]
            ]);
            
            if ((int) $result !== (int) count($this->models)) {
                throw new ErrorException($this->methodError('deleteAll'));
            }
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array EmailsMailingsArrayRemover::models
     * @param array $models EmailsMailingsModel
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

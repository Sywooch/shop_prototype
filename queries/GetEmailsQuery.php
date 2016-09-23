<?php

namespace app\queries;

use yii\base\ErrorException;
use yii\db\ActiveQuery;
use app\queries\AbstractBaseQuery;
use app\models\EmailsModel;

/**
 * Конструирует объект запроса, возвращающий массив объектов EmailsModel
 */
class GetEmailsQuery extends AbstractBaseQuery
{
    public function __construct($config=[])
    {
        try {
            $this->className = EmailsModel::className();
            parent::__construct($config);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function getAll()
    {
    }
    
    /**
     * Конфигурирует объект запроса yii\db\ActiveQuery для выборки одной строки
     * @return object ActiveQuery
     */
    public function getOne(): ActiveQuery
    {
        try {
            if (!$this->getSelect()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
            }
            
            if (!$this->extraWhere()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
            }
            
           return $this->query;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

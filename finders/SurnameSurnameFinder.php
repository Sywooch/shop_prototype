<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\SurnamesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает SurnamesModel из СУБД
 */
class SurnameSurnameFinder extends AbstractBaseFinder
{
    /**
     * @var string surname
     */
    public $surname;
    /**
     * @var SurnamesModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                if (empty($this->surname)) {
                    throw new ErrorException($this->emptyError('surname'));
                }
                
                $query = SurnamesModel::find();
                $query->select(['[[surnames.id]]', '[[surnames.surname]]']);
                $query->where(['[[surnames.surname]]'=>$this->surname]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

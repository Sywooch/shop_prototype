<?php

namespace app\queries;

use yii\base\{ErrorException,
    Object};
use yii\data\Pagination;
use app\exceptions\ExceptionsTrait;
use app\queries\{QueryInterface,
    QueryTrait};

/**
 * Абстрактный суперкласс построения запроса к БД
 */
abstract class AbstractBaseQuery extends Object implements QueryInterface
{
    use ExceptionsTrait, QueryTrait;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->className)) {
                throw new ErrorException(\Yii::t('base/errors', 'Not Evaluated {placeholder}!', ['placeholder'=>'$className']));
            }
            
            $this->query = $this->className::find();
            $this->tableName = $this->className::tableName();
            
            if (empty($this->paginator)) {
                $this->paginator = new Pagination();
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

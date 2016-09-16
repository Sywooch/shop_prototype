<?php

namespace app\queries;

use yii\base\{ErrorException,
    Object};
use yii\sphinx\{MatchExpression,
    Query};
use app\queries\QueryInterface;
use app\traits\{ExceptionsTrait,
    QueryTrait};

/**
 * Конструирует объект запроса, возвращающий массив данных сервера Sphinx
 */
class GetSphinxQuery extends Object implements QueryInterface
{
    use ExceptionsTrait, QueryTrait;
    
    /**
     * @var object yii\sphinx\MatchExpression
     */
    public $match;
    /**
     * @vat string поисковый запрос
     */
    public $text;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->tableName)) {
                throw new ErrorException(\Yii::t('base/errors', 'Not Evaluated {placeholder}!', ['placeholder'=>'$tableName']));
            }
            if (empty($this->text)) {
                throw new ErrorException(\Yii::t('base/errors', 'Not Evaluated {placeholder}!', ['placeholder'=>'$text']));
            }
            
            $this->query = new Query();
            $this->match = new MatchExpression();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Конфигурирует объект запроса для выборки массива строк
     * @return object ActiveQuery
     */
    public function getAll()
    {
        try {
            $this->query->select($this->fields);
            
            $this->query->from($this->tableName);
            
            $this->match->match(['*'=>$this->text]);
            
            $this->query->match($this->match);
            
            return $this->query;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function getOne()
    {
    }
}

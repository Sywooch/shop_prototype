<?php

namespace app\queries;

use yii\base\Object;
use app\exceptions\ExceptionsTrait;
use app\queries\{Filter,
    Formatter,
    Sorter};

class DataProvider extends Object
{
    use ExceptionsTrait;
    
    public $query;
    public $filters = [];
    public $postFormat = [];
    public $postSorting = [];
    
    private $_data;
    
    public function all()
    {
        try {
            $this->preprocessing();
            
            $this->_data = $this->query->all();
            
            $this->postprocessing();
            
            return $this->_data;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    private function preprocessing()
    {
        try {
            if (!empty($this->filters)) {
                $this->query = Filter::setFilter($this->query, $this->filters);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    private function postprocessing()
    {
        try {
            if (!empty($this->postFormat)) {
                $this->_data = Formatter::setFormat($this->_data, $this->postFormat);
            }
            
            if (!empty($this->postSorting)) {
                $this->_data = Sorter::setSorting($this->_data, $this->postSorting);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

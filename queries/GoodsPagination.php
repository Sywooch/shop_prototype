<?php

namespace app\queries;

use yii\base\ErrorException;
use yii\data\Pagination;
use app\queries\PaginationInterface;
use app\exceptions\ExceptionsTrait;

class GoodsPagination extends Pagination implements PaginationInterface
{
    use ExceptionsTrait;
    
    /**
     * @var array массив данных запроса
     */
    private $request;
    
    /**
     * Сохраняет массив данных запроса во внутреннем свойстве
     * @param array $request
     */
    public function setRequest(array $request)
    {
        try {
            $this->request = $request;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Конфигурирует объект Pagination
     * @param object $query Query
     */
    public function configure($query)
    {
        try {
            $this->pageSize = \Yii::$app->params['limit'];
            $this->page = !empty($this->request[\Yii::$app->params['pagePointer']]) ? $this->request[\Yii::$app->params['pagePointer']] - 1 : 0;
            
            $countQuery = clone $query;
            
            $this->totalCount = $countQuery->count();
            
            if ($this->page > $this->pageCount - 1) {
                $this->page = $this->pageCount - 1;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

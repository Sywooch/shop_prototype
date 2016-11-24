<?php

namespace app\queries;

use yii\base\{ErrorException,
    Object};
use app\queries\PaginationInterface;
use app\exceptions\ExceptionsTrait;

class LightPagination extends Object implements PaginationInterface
{
    use ExceptionsTrait;
    
    /**
     * @var int количество строк, доступных в СУБД до применения ограничений LIMIT и OFFSET
     */
    private $totalCount = 0;
    /**
     * @var int количество записей, выводимых на одной странице
     */
    private $pageSize = 0;
    /**
     * @var int номер страницы
     */
    private $page = 0;
    
    /**
     * Конфигурирует объект Pagination, устанавливая количество строк, 
     * доступных в СУБД до применения ограничений LIMIT и OFFSET
     * @param object $query Query
     */
    public function configure($query)
    {
        try {
            $countQuery = clone $query;
            $this->totalCount = $countQuery->count();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает количество страниц
     * @return int
     */
    public function getPageCount(): int
    {
        try {
            return (int) ceil($this->totalCount / $this->pageSize);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает смещение выборки в зависимости от запрашиваемой страницы
     * @return int
     */
    public function getOffset(): int
    {
        try {
            return $this->pageSize < 1 ? 0 : $this->page * $this->pageSize;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает количество записей, возвращаемых из СУБД
     * @return int
     */
    public function getLimit(): int
    {
        try {
            return $this->pageSize < 1 ? 1 : $this->pageSize;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет количество записей, выводимых на одной странице
     * @param int $size
     */
    public function setPageSize(int $size)
    {
        try {
            $this->pageSize = $size;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет номер страницы
     * @param int $size
     */
    public function setPage(int $number)
    {
        try {
            $this->page = $number;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

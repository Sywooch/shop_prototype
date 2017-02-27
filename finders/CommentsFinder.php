<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\CommentsModel;
use app\finders\AbstractBaseFinder;
use app\collections\{LightPagination,
    CommentsCollection};
use app\filters\CommentsFiltersInterface;

/**
 * Возвращает пользователя с указанным email
 */
class CommentsFinder extends AbstractBaseFinder
{
    /**
     * @var string GET параметр, определяющий текущую страницу
     */
    private $page;
    /**
     * @var CommentsFiltersInterface объект товарных фильтров
     */
    private $filters;
    /**
     * @var array CommentsModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->filters)) {
                throw new ErrorException($this->emptyError('filters'));
            }
            
            if (empty($this->storage)) {
                $this->storage = new CommentsCollection(['pagination'=>new LightPagination()]);
                
                $query = CommentsModel::find();
                $query->select(['[[comments.id]]', '[[comments.date]]', '[[comments.text]]', '[[comments.id_name]]', '[[comments.id_email]]', '[[comments.id_product]]', '[[comments.active]]']);
                $query->with('email', 'name', 'product');
                
                $this->storage->pagination->pageSize = \Yii::$app->params['limit'];
                $this->storage->pagination->page = !empty($this->page) ? (int) $this->page - 1 : 0;
                $this->storage->pagination->setTotalCount($query);
                
                $query->offset($this->storage->pagination->offset);
                $query->limit($this->storage->pagination->limit);
                
                $sortingField = $this->filters->sortingField ?? \Yii::$app->params['sortingField'];
                $sortingType = $this->filters->sortingType ?? \Yii::$app->params['sortingType'];
                $query->orderBy(['[[comments.' . $sortingField . ']]'=>(int) $sortingType]);
                
                $commentsArray = $query->all();
                
                if (!empty($commentsArray)) {
                    foreach ($commentsArray as $user) {
                        $this->storage->add($user);
                    }
                }
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение CommentsFinder::page
     * @param int $page
     */
    public function setPage(int $page)
    {
        try {
            $this->page = $page;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение CommentsFinder::filters
     * @param ProductsFiltersInterface $filters
     */
    public function setFilters(CommentsFiltersInterface $filters)
    {
        try {
            $this->filters = $filters;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

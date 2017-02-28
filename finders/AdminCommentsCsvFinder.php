<?php

namespace app\finders;

use yii\base\ErrorException;
use yii\db\ActiveQuery;
use app\finders\AbstractBaseFinder;
use app\models\CommentsModel;
use app\filters\CommentsFiltersInterface;

/**
 * Возвращает заказы из СУБД
 */
class AdminCommentsCsvFinder extends AbstractBaseFinder
{
    /**
     * @var CommentsFiltersInterface
     */
    private $filters;
    /**
     * @var PurchasesCollection
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return ActiveQuery
     */
    public function find(): ActiveQuery
    {
        try {
            if (empty($this->filters)) {
                throw new ErrorException($this->emptyError('filters'));
            }
            
            if (empty($this->storage)) {
                $query = CommentsModel::find();
                $query->select(['[[comments.id]]', '[[comments.date]]', '[[comments.text]]', '[[comments.id_name]]', '[[comments.id_email]]', '[[comments.id_product]]', '[[comments.active]]']);
                $query->with('email', 'name', 'product');
                
                if ($this->filters->activeStatus === ACTIVE_STATUS || $this->filters->activeStatus === INACTIVE_STATUS) {
                    $query->where(['[[comments.active]]'=>$this->filters->activeStatus]);
                }
                
                $sortingField = $this->filters->sortingField ?? \Yii::$app->params['sortingField'];
                $sortingType = $this->filters->sortingType ?? \Yii::$app->params['sortingType'];
                $query->orderBy(['[[comments.' . $sortingField . ']]'=>(int) $sortingType]);
                
                $this->storage = $query;
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CommentsFiltersInterface ProductsFinder::filters
     * @param CommentsFiltersInterface $filters
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

<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\models\CommentsModel;

/**
 * Возвращает массив комментариев к товару
 */
class CommentIdFinder extends AbstractBaseFinder
{
    /**
     * @var int ID комментария
     */
    private $id;
    /**
     * @var array загруженных CommentsModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->id)) {
                throw new ErrorException($this->emptyError('id'));
            }
            
            if (empty($this->storage)) {
                $query = CommentsModel::find();
                $query->select(['[[comments.id]], [[comments.date]], [[comments.text]], [[comments.id_name]], [[comments.id_email]], [[comments.id_product]], [[comments.active]]']);
                $query->with('name', 'email', 'product');
                $query->where(['[[comments.id]]'=>$this->id]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение CommentsFinder::id
     * @param int $id
     */
    public function setId(int $id)
    {
        try {
            $this->id = $id;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

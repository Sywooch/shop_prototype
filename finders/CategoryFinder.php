<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\CategoriesModel;
use app\collections\CollectionInterface;

/**
 * Возвращает объект категории
 */
class CategoryFinder extends AbstractBaseFinder
{
    /**
     * @var int ID искомой категории
     */
    public $id;
    
    public function rules()
    {
        return [
            [['id'], 'required']
        ];
    }
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find(): CollectionInterface
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException($this->emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                if ($this->validate() === false) {
                    throw new ErrorException($this->modelError($this->errors));
                }
                
                $query = CategoriesModel::find();
                $query->select(['[[categories.name]]', '[[categories.seocode]]']);
                $query->where(['[[categories.id]]'=>$this->id]);
                
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

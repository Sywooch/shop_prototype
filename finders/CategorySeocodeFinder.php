<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\CategoriesModel;

/**
 * Возвращает объект категории
 */
class CategorySeocodeFinder extends AbstractBaseFinder
{
    /**
     * @var string GET параметр, определяющий искомую категорию
     */
    public $category;
    
    public function rules()
    {
        return [
            [['category'], 'required']
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
                $query->where(['[[categories.seocode]]'=>$this->category]);
                
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

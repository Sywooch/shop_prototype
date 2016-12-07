<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\SubcategoryModel;
use app\collections\CollectionInterface;

/**
 * Возвращает объект категории
 */
class SubcategorySeocodeFinder extends AbstractBaseFinder
{
    /**
     * @var string GET параметр, определяющий искомую категорию
     */
    public $seocode;
    
    public function rules()
    {
        return [
            [['seocode'], 'required']
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
                
                $query = SubcategoryModel::find();
                $query->select(['[[subcategory.name]]', '[[subcategory.seocode]]']);
                $query->where(['[[subcategory.seocode]]'=>$this->seocode]);
                
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

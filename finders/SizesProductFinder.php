<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\SizesModel;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;

/**
 * Возвращает коллекцию цветов, связанных с определенным товаром из СУБД
 */
class SizesProductFinder extends AbstractBaseFinder
{
    /**
     * @var int ID товара, для которого будут получены цвета
     */
    public $id_product;
    
    public function rules()
    {
        return [
            [['id_product'], 'required']
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
            
            if ($this->collection->isEmpty() === true) {
                if ($this->validate() === false) {
                    throw new ErrorException($this->modelError($this->errors));
                }
                
                $query = SizesModel::find();
                $query->select(['[[sizes.id]]', '[[sizes.size]]']);
                $query->innerJoin('{{products_sizes}}', '[[sizes.id]]=[[products_sizes.id_size]]');
                $query->where(['[[products_sizes.id_product]]'=>$this->id_product]);
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

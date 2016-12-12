<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ColorsModel;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;

/**
 * Возвращает коллекцию цветов, связанных с определенным товаром из СУБД
 */
class ColorsProductFinder extends AbstractBaseFinder
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
                
                $query = ColorsModel::find();
                $query->select(['[[colors.id]]', '[[colors.color]]']);
                $query->innerJoin('{{products_colors}}', '[[colors.id]]=[[products_colors.id_color]]');
                $query->where(['[[products_colors.id_product]]'=>$this->id_product]);
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

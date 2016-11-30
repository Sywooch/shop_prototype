<?php

namespace app\finders;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\models\SizesModel;
use app\finders\FinderInterface;
use app\collections\CollectionInterface;

class SizesFilterFinder extends Model implements FinderInterface
{
    use ExceptionsTrait;
    
    /**
     * @var string GET параметр, определяющий текущую категорию каталога товаров
     */
    public $category;
    /**
     * @var string GET параметр, определяющий текущую подкатегорию каталога товаров
     */
    public $subcategory;
    /**
     * @var object CollectionInterface
     */
    private $collection;
    
    public function rules()
    {
        return [
            [['category', 'subcategory'], 'safe']
        ];
    }
    
    /**
     * Загружает данные в свойства модели
     * @param $data массив данных
     * @return bool
     */
    public function load($data, $formName=null)
    {
        try {
            return parent::load($data, '');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find(): CollectionInterface
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                $query = SizesModel::find();
                $query->select(['[[sizes.id]]', '[[sizes.size]]']);
                $query->distinct();
                $query->innerJoin('{{products_sizes}}', '[[sizes.id]]=[[products_sizes.id_size]]');
                $query->innerJoin('{{products}}', '[[products_sizes.id_product]]=[[products.id]]');
                $query->where(['[[products.active]]'=>true]);
                
                if (!empty($this->category)) {
                    $query->innerJoin('{{categories}}', '[[products.id_category]]=[[categories.id]]');
                    $query->andWhere(['[[categories.seocode]]'=>$this->category]);
                    if (!empty($this->subcategory)) {
                        $query->innerJoin('{{subcategory}}', '[[products.id_subcategory]]=[[subcategory.id]]');
                        $query->andWhere(['[[subcategory.seocode]]'=>$this->subcategory]);
                    }
                }
                
                $sizesArray = $query->all();
                
                if (!empty($sizesArray)) {
                    foreach ($sizesArray as $size) {
                        $this->collection->add($size);
                    }
                }
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству SizesFilterFinder::collection
     * @param object $collection CollectionInterface
     */
    public function setCollection(CollectionInterface $collection)
    {
        try {
            $this->collection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

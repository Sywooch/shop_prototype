<?php

namespace app\finders;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\models\BrandsModel;
use app\finders\FinderInterface;
use app\collections\CollectionInterface;

class BrandsFilterFinder extends Model implements FinderInterface
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
                $query = BrandsModel::find();
                $query->select(['[[brands.id]]', '[[brands.brand]]']);
                $query->distinct();
                $query->innerJoin('{{products}}', '[[products.id_brand]]=[[brands.id]]');
                $query->where(['[[products.active]]'=>true]);
                
                if (!empty($this->category)) {
                    $query->innerJoin('{{categories}}', '[[products.id_category]]=[[categories.id]]');
                    $query->andWhere(['[[categories.seocode]]'=>$this->category]);
                    if (!empty($this->subcategory)) {
                        $query->innerJoin('{{subcategory}}', '[[products.id_subcategory]]=[[subcategory.id]]');
                        $query->andWhere(['[[subcategory.seocode]]'=>$this->subcategory]);
                    }
                }
                
                $brandsArray = $query->all();
                
                if (!empty($brandsArray)) {
                    foreach ($brandsArray as $brand) {
                        $this->collection->add($brand);
                    }
                }
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству BrandsFilterFinder::collection
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

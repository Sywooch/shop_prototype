<?php

namespace app\finders;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;
use app\finders\FinderInterface;
use app\collections\CollectionInterface;

class ProductsFinder extends Model implements FinderInterface
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
     * @var string GET параметр, определяющий текущую страницу каталога товаров
     */
    public $page;
    /**
     * @var object CollectionInterface
     */
    private $collection;
    
    public function rules()
    {
        return [
            [['category', 'subcategory', 'page'], 'safe']
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
                $query = ProductsModel::find();
                $query->select(['[[products.name]]', '[[products.price]]', '[[products.short_description]]', '[[products.images]]', '[[products.seocode]]']);
                $query->where(['[[products.active]]'=>true]);
                if (!empty($this->category)) {
                    $query->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
                    $query->where(['[[categories.seocode]]'=>$this->category]);
                    if (!empty($this->subcategory)) {
                        $query->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
                        $query->andWhere(['[[subcategory.seocode]]'=>$this->subcategory]);
                    }
                }
                
                $this->collection->pagination->pageSize = \Yii::$app->params['limit'];
                $this->collection->pagination->page = !empty($this->page) ? (int) $this->page - 1 : 0;
                $this->collection->pagination->setTotalCount($query);
                
                $query->offset($this->collection->pagination->offset);
                $query->limit($this->collection->pagination->limit);
                $query->orderBy(['[[products.date]]'=>SORT_DESC]);
                
                $productsArray = $query->all();
                
                if (!empty($productsArray)) {
                    foreach ($productsArray as $product) {
                        $this->collection->add($product);
                    }
                }
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству ProductsFinder::collection
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

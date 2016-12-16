<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\SizesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает коллекцию цветов из СУБД
 */
class SizesFilterFinder extends AbstractBaseFinder
{
    /**
     * @var string GET параметр, определяющий текущую категорию каталога товаров
     */
    public $category;
    /**
     * @var string GET параметр, определяющий текущую подкатегорию каталога товаров
     */
    public $subcategory;
    /**
     * @var массив загруженных SizesModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                $query = SizesModel::find();
                $query->select(['[[sizes.id]]', '[[sizes.size]]']);
                $query->distinct();
                $query->innerJoin('{{products_sizes}}', '[[sizes.id]]=[[products_sizes.id_size]]');
                $query->innerJoin('{{products}}', '[[products_sizes.id_product]]=[[products.id]]');
                $query->where(['[[products.active]]'=>true]);
            
                if (!empty($this->category)) {
                    $query->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
                    $query->andWhere(['[[categories.seocode]]'=>$this->category]);
                    if (!empty($this->subcategory)) {
                        $query->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
                        $query->andWhere(['[[subcategory.seocode]]'=>$this->subcategory]);
                    }
                }
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

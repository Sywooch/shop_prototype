<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\BrandsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает коллекцию цветов из СУБД
 */
class BrandsFilterFinder extends AbstractBaseFinder
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
     * @var массив загруженных BrandsModel
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
                $query = BrandsModel::find();
                $query->select(['[[brands.id]]', '[[brands.brand]]']);
                $query->distinct();
                $query->innerJoin('{{products}}', '[[products.id_brand]]=[[brands.id]]');
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

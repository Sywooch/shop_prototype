<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ColorsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает коллекцию цветов из СУБД
 */
class ColorsFilterFinder extends AbstractBaseFinder
{
    /**
     * @var string GET параметр, определяющий текущую категорию каталога товаров
     */
    private $category;
    /**
     * @var string GET параметр, определяющий текущую подкатегорию каталога товаров
     */
    private $subcategory;
    /**
     * @var массив загруженных ColorsModel
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
                $query = ColorsModel::find();
                $query->select(['[[colors.id]]', '[[colors.color]]', '[[colors.hexcolor]]']);
                $query->distinct();
                $query->innerJoin('{{products_colors}}', '[[colors.id]]=[[products_colors.id_color]]');
                $query->innerJoin('{{products}}', '[[products_colors.id_product]]=[[products.id]]');
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
    
    /**
     * Присваивает категорию свойству ColorsFilterFinder::category
     * @param string $category
     */
    public function setCategory(string $category)
    {
        try {
            $this->category = $category;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает подкатегорию свойству ColorsFilterFinder::subcategory
     * @param string $subcategory
     */
    public function setSubcategory(string $subcategory)
    {
        try {
            $this->subcategory = $subcategory;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

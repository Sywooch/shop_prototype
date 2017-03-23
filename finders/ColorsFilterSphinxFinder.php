<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ColorsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает коллекцию цветов из СУБД
 */
class ColorsFilterSphinxFinder extends AbstractBaseFinder
{
    /**
     * @var array массив ID товаров, найденный sphinx в ответ на запрос
     */
    private $sphinx;
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
            if (empty($this->sphinx)) {
                throw new ErrorException($this->emptyError('sphinx'));
            }
            
            if (empty($this->storage)) {
                $query = ColorsModel::find();
                $query->select(['[[colors.id]]', '[[colors.color]]', '[[colors.hexcolor]]']);
                $query->distinct();
                $query->innerJoin('{{products_colors}}', '[[colors.id]]=[[products_colors.id_color]]');
                $query->innerJoin('{{products}}', '[[products_colors.id_product]]=[[products.id]]');
                $query->where(['[[products.active]]'=>true]);
            
                $query->andWhere(['[[products.id]]'=>$this->sphinx]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array ColorsFilterSphinxFinder::sphinx
     * @param array $sphinx
     */
    public function setSphinx(array $sphinx)
    {
        try {
            $this->sphinx = $sphinx;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

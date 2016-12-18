<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\SizesModel;
use app\finders\{AbstractBaseFinder,
    SizesFilterFindersTrait};

/**
 * Возвращает коллекцию цветов из СУБД
 */
class SizesFilterSphinxFinder extends AbstractBaseFinder
{
    use SizesFilterFindersTrait;
    
    /**
     * @var array массив ID товаров, найденный sphinx в ответ на запрос
     */
    private $sphinx;
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
                if (empty($this->sphinx)) {
                    throw new ErrorException($this->emptyError('sphinx'));
                }
                
                $query = $this->createQuery();
            
                $query->andWhere(['[[products.id]]'=>$this->sphinx]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array SizesFilterSphinxFinder::sphinx
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

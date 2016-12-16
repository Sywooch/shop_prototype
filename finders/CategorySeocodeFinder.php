<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\CategoriesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные категории товаров из СУБД
 */
class CategorySeocodeFinder extends AbstractBaseFinder
{
    /**
     * @var string seocode категории
     */
    public $seocode;
    /**
     * @var array массив загруженных CategoriesModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                if (empty($this->seocode)) {
                    throw new ErrorException($this->emptyError('seocode'));
                }
                
                $query = CategoriesModel::find();
                $query->select(['[[categories.id]]', '[[categories.name]]', '[[categories.seocode]]', '[[categories.active]]']);
                $query->where(['[[categories.seocode]]'=>$this->seocode]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

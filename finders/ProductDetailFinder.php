<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ProductsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает ProductsModel выбранного товара из СУБД
 */
class ProductDetailFinder extends AbstractBaseFinder
{
    /**
     * @var string GET параметр, определяющий запрашиваемый товар
     */
    public $seocode;
    /**
     * @var загруженный ProductsModel
     */
    private $storage = null;
    
    public function rules()
    {
        return [
            [['seocode'], 'required']
        ];
    }
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                if ($this->validate() === false) {
                    throw new ErrorException($this->modelError($this->errors));
                }
                
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.code]]', '[[products.name]]', '[[products.price]]', '[[products.description]]', '[[products.images]]', '[[products.seocode]]', '[[products.id_category]]', '[[products.id_subcategory]]']);
                $query->where(['[[products.seocode]]'=>$this->seocode]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

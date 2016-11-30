<?php

namespace app\finders;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\finders\FinderInterface;
use app\collections\CollectionInterface;
use app\models\CategoriesModel;

class CategorySeocodeFinder extends Model implements FinderInterface
{
    use ExceptionsTrait;
    
    /**
     * @var string GET параметр, определяющий текущую категорию каталога товаров
     */
    public $seocode;
    /**
     * @var object Model
     */
    private $entity;
    
    public function rules()
    {
        return [
            [['seocode'], 'required']
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
     * @return mixed Model/null
     */
    public function find()
    {
        try {
            if (empty($this->entity)) {
                if ($this->validate()) {
                    $query = CategoriesModel::find();
                    $query->where(['[[seocode]]'=>$this->seocode]);
                    $this->entity = $query->one();
                }
            }
            
            return !empty($this->entity) ? $this->entity : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

/**
 * Представляет данные формы добавления коментария
 */
class ProductsForm extends AbstractBaseForm
{
    /**
     * Сценарий создания нового товара
     */
    const CREATE = 'create';
    /**
     * Сценарий редактирования товара
     */
    const EDIT = 'edit';
    
    /**
     * @var int ID товара
     */
    public $id;
    /**
     * @var string товара
     */
    public $code;
    /**
     * @var string название товара
     */
    public $name;
    /**
     * @var string краткое описание товара
     */
    public $short_description;
    /**
     * @var string description описание товара
     */
    public $description;
    /**
     * @var float цена товара
     */
    public $price;
    /**
     * @var string имя каталога с изображениями
     */
    public $images;
    /**
     * @var int ID категории товара
     */
    public $id_category;
    /**
     * @var int ID подкатегории товара
     */
    public $id_subcategory;
    /**
     * @var array ID цветов товара
     */
    public $id_colors;
    /**
     * @var array ID размеров товара
     */
    public $id_sizes;
    /**
     * @var int ID бренда
     */
    public $id_brand;
    /**
     * @var bool активен ли товар
     */
    public $active;
    /**
     * @var int количество доступных товаров
     */
    public $total_products;
    /**
     * @var string сеокод товара
     */
    public $seocode;
    /**
     * @var int количество просмотров
     */
    public $views;
    
    public function scenarios()
    {
        return [
            self::CREATE=>['code', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'id_colors', 'id_sizes', 'id_brand', 'active', 'total_products', 'seocode'],
            self::EDIT=>['id', 'code', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'id_colors', 'id_sizes', 'id_brand', 'active', 'total_products', 'seocode', 'views'],
        ];
    }
    
    public function rules()
    {
        return [
            [['code', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'id_colors', 'id_sizes', 'id_brand', 'active', 'total_products', 'seocode'], 'required', 'on'=>self::CREATE],
            [['id'], 'required', 'on'=>self::EDIT]
        ];
    }
}

<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

/**
 * Представляет данные формы для изменения заказа
 */
class AdminChangeProductForm extends AbstractBaseForm
{
    /**
     * Сценарий запроса формы для редактирования товара
     */
    const GET = 'get';
    /**
     * Сценарий сохранения изменений в описании товара
     */
    const SAVE = 'save';
    
    /**
     * @var int Id заказа
     */
    public $id;
    /**
     * @var string код товара
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
     * @var string описание товара
     */
    public $description;
    /**
     * @var float цена товара
     */
    public $price;
    /**
     * @var string имя директории с изображениями
     */
    public $images;
    /**
     * @var int Id категории
     */
    public $id_category;
    /**
     * @var int Id подкатегории
     */
    public $id_subcategory;
    /**
     * @var int Id бренда
     */
    public $id_brand;
    /**
     * @var bool активен или нет товар
     */
    public $active;
    /**
     * @var int количество товаров на складе
     */
    public $total_products;
    /**
     * @var string seocode
     */
    public $seocode;
    /**
     * @var int количество просмотров
     */
    public $views;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['id', 'code', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'id_brand', 'active', 'total_products', 'seocode', 'views'],
            self::GET=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'code', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'id_brand', 'active', 'total_products', 'seocode', 'views'], 'required', 'on'=>self::SAVE],
            [['id'], 'required', 'on'=>self::GET],
        ];
    }
}

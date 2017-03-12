<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{ActiveStatusExistsValidator,
    CreateProductCodeExistsValidator,
    EditProductCodeExistsValidator,
    IntInArrayValidator,
    RelatedProductsExistsValidator,
    StripTagsValidator};

/**
 * Представляет данные формы добавления коментария
 */
class AdminProductForm extends AbstractBaseForm
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
     * Сценарий запроса формы редактирования
     */
    const GET = 'get';
    /**
     * Сценарий удаления товара
     */
    const DELETE = 'delete';
    
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
     * @var mixed имя каталога с изображениями / загружаемые изображения
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
     * @var int активен ли товар
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
    /**
     * @var int связанных товаров
     */
    public $related;
    
    public function scenarios()
    {
        return [
            self::CREATE=>['code', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'id_colors', 'id_sizes', 'id_brand', 'active', 'total_products', 'seocode', 'related'],
            self::EDIT=>['id', 'code', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'id_colors', 'id_sizes', 'id_brand', 'active', 'total_products', 'seocode', 'views', 'related'],
            self::GET=>['id'],
            self::DELETE=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'code', 'name', 'short_description', 'description', 'price', 'id_category', 'id_subcategory', 'id_colors', 'id_sizes', 'id_brand', 'active', 'total_products', 'seocode', 'views', 'related'], StripTagsValidator::class],
            [['id'], 'required', 'on'=>self::GET],
            [['id'], 'required', 'on'=>self::DELETE],
            [['code', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'id_colors', 'id_sizes', 'id_brand'], 'required', 'on'=>self::CREATE],
            [['id', 'code', 'name', 'short_description', 'description', 'price', 'id_category', 'id_subcategory', 'id_colors', 'id_sizes', 'id_brand', 'seocode'], 'required', 'on'=>self::EDIT],
            [['images'], 'image', 'extensions'=>['png', 'jpg', 'gif'], 'minWidth'=>200, 'minHeight'=>200, 'maxWidth'=>800, 'maxHeight'=>800, 'maxFiles'=>5, 'maxSize'=>1024*512, 'mimeTypes'=>'image/*'],
            [['name', 'short_description', 'description', 'related', 'code'], 'string'],
            [['id', 'id_category', 'id_subcategory', 'id_brand', 'total_products', 'views'], 'integer'],
            [['name', 'short_description', 'description'], 'match', 'pattern'=>'#[a-zа-я0-9\s]#iu'],
            [['code'], 'match', 'pattern'=>'#^[A-Z0-9-]+$#u'],
            [['price'], 'double'],
            [['active'], ActiveStatusExistsValidator::class],
            [['id_colors', 'id_sizes'], IntInArrayValidator::class],
            [['seocode'], 'match', 'pattern'=>'#^[a-z0-9-]+$#u'],
            [['related'], 'match', 'pattern'=>'#^(?:[0-9]{1,3},?)+$#u'],
            [['related'], RelatedProductsExistsValidator::class],
            [['code'], CreateProductCodeExistsValidator::class, 'on'=>self::CREATE],
            [['code'], EditProductCodeExistsValidator::class, 'on'=>self::EDIT],
        ];
    }
}

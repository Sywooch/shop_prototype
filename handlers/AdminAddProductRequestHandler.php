<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{BrandsFinder,
    CategoriesFinder,
    ColorsFinder,
    SizesFinder};
use app\forms\{AbstractBaseForm,
    AdminProductForm};

/**
 * Обрабатывает запрос на получение данных 
 * для формы добавления нового товара
 */
class AdminAddProductRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * @param yii\web\Request $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                $categoriesArray = $finder->find();
                if (empty($categoriesArray)) {
                    throw new ErrorException($this->emptyError('categoriesArray'));
                }
                
                $finder = \Yii::$app->registry->get(ColorsFinder::class);
                $colorsArray = $finder->find();
                if (empty($colorsArray)) {
                    throw new ErrorException($this->emptyError('colorsArray'));
                }
                
                $finder = \Yii::$app->registry->get(SizesFinder::class);
                $sizesArray = $finder->find();
                if (empty($sizesArray)) {
                    throw new ErrorException($this->emptyError('sizesArray'));
                }
                
                $finder = \Yii::$app->registry->get(BrandsFinder::class);
                $brandsArray = $finder->find();
                if (empty($brandsArray)) {
                    throw new ErrorException($this->emptyError('brandsArray'));
                }
                
                $adminProductForm = new AdminProductForm();
                
                $dataArray['adminAddProductFormWidgetConfig'] = $this->adminAddProductFormWidgetConfig($categoriesArray, $colorsArray, $sizesArray, $brandsArray, $adminProductForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\handlers\AbstractBaseHandler;
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
                
                $adminProductForm = new AdminProductForm(['scenario'=>AdminProductForm::CREATE]);
                
                $dataArray['adminAddProductFormWidgetConfig'] = $this->adminAddProductFormWidgetConfig($categoriesArray, $colorsArray, $sizesArray, $brandsArray, $adminProductForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminAddProductFormWidget
     * @param array $categoriesArray
     * @param array $colorsArray
     * @param array $sizesArray
     * @param array $brandsArray
     * @param AbstractBaseForm $adminProductForm
     * @return array
     */
    private function adminAddProductFormWidgetConfig(array $categoriesArray, array $colorsArray, array $sizesArray, array $brandsArray, AbstractBaseForm $adminProductForm): array
    {
        try {
            $dataArray = [];
            
            ArrayHelper::multisort($categoriesArray, 'name');
            $categoriesArray = ArrayHelper::map($categoriesArray, 'id', 'name');
            $dataArray['categories'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $categoriesArray);
            
            $dataArray['subcategory'] = [\Yii::$app->params['formFiller']];
            
            ArrayHelper::multisort($colorsArray, 'color');
            $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
            
            ArrayHelper::multisort($sizesArray, 'size');
            $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
            
            ArrayHelper::multisort($brandsArray, 'brand');
            $brandsArray = ArrayHelper::map($brandsArray, 'id', 'brand');
            $dataArray['brands'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $brandsArray);
            
            $dataArray['form'] = $adminProductForm;
            $dataArray['template'] = 'admin-add-product-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\finders\CategoriesFinder;
use app\forms\{AbstractBaseForm,
    CategoriesForm,
    SubcategoryForm};

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем заказов
 */
class AdminCategoriesRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param yii\web\Request $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                $categoriesModelArray = $finder->find();
                
                $categoriesForm = new CategoriesForm(['scenario'=>CategoriesForm::DELETE]);
                $subcategoryForm = new SubcategoryForm(['scenario'=>SubcategoryForm::DELETE]);
                
                $dataArray = [];
                
                $dataArray['adminCategoriesWidgetConfig'] = $this->adminCategoriesWidgetConfig($categoriesModelArray, $categoriesForm, $subcategoryForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

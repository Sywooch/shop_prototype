<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\models\SubcategoryModel;
use app\savers\ModelSaver;
use app\finders\CategoriesFinder;
use app\forms\{CategoriesForm,
    SubcategoryForm};
use app\widgets\AdminCategoriesWidget;

/**
 * Обрабатывает запрос на создание подкатегории
 */
class AdminCategoriesSubcategoryCreateRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Создает подкатегорию
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new SubcategoryForm(['scenario'=>SubcategoryForm::CREATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $rawSubcategoryModel = new SubcategoryModel(['scenario'=>SubcategoryModel::CREATE]);
                        $rawSubcategoryModel->name = $form->name;
                        $rawSubcategoryModel->seocode = $form->seocode;
                        $rawSubcategoryModel->id_category = $form->id_category;
                        $rawSubcategoryModel->active = $form->active;
                        if ($rawSubcategoryModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawSubcategoryModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawSubcategoryModel
                        ]);
                        $saver->save();
                        
                        $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                        $categoriesModelArray = $finder->find();
                        
                        $categoriesForm = new CategoriesForm(['scenario'=>CategoriesForm::DELETE]);
                        $subcategoryForm = new SubcategoryForm(['scenario'=>SubcategoryForm::DELETE]);
                        
                        $adminCategoriesWidgetConfig = $this->adminCategoriesWidgetConfig($categoriesModelArray, $categoriesForm, $subcategoryForm);
                        $response = AdminCategoriesWidget::widget($adminCategoriesWidgetConfig);
                        
                        $transaction->commit();
                        
                        return $response;
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        throw $t;
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

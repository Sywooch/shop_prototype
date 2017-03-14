<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\{CategoriesForm,
    SubcategoryForm};
use app\finders\{CategoriesFinder,
    SubcategoryIdFinder};
use app\models\SubcategoryModel;
use app\removers\SubcategoryModelRemover;
use app\widgets\AdminCategoriesWidget;

/**
 * Обрабатывает запрос на обновление данных товара
 */
class AdminCategoriesSubcategoryDeleteRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Обновляет данные товара
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new SubcategoryForm(['scenario'=>SubcategoryForm::DELETE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(SubcategoryIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $subcategoryModel = $finder->find();
                        
                        $subcategoryModel->scenario = SubcategoryModel::DELETE;
                        if ($subcategoryModel->validate() === false) {
                            throw new ErrorException($this->modelError($subcategoryModel->errors));
                        }
                        
                        $remover = new SubcategoryModelRemover([
                            'model'=>$subcategoryModel
                        ]);
                        $remover->remove();
                        
                        $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                        $categoriesModelArray = $finder->find();
                        
                        $categoriesForm = new CategoriesForm();
                        $subcategoryForm = new SubcategoryForm();
                        
                        $dataArray = [];
                        
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

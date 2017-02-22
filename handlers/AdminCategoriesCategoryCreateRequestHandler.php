<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\models\CategoriesModel;
use app\savers\ModelSaver;
use app\finders\CategoriesFinder;
use app\forms\{CategoriesForm,
    SubcategoryForm};
use app\widgets\{AdminCategoriesWidget,
    CategoriesOptionWidget};

/**
 * Обрабатывает запрос на создание категории
 */
class AdminCategoriesCategoryCreateRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Создает категорию
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new CategoriesForm(['scenario'=>CategoriesForm::CREATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $rawCategoriesModel = new CategoriesModel(['scenario'=>CategoriesModel::CREATE]);
                        $rawCategoriesModel->name = $form->name;
                        $rawCategoriesModel->seocode = $form->seocode;
                        $rawCategoriesModel->active = $form->active;
                        if ($rawCategoriesModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawCategoriesModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawCategoriesModel
                        ]);
                        $saver->save();
                        
                        $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                        $categoriesModelArray = $finder->find();
                        
                        $categoriesForm = new CategoriesForm(['scenario'=>CategoriesForm::DELETE]);
                        $subcategoryForm = new SubcategoryForm(['scenario'=>SubcategoryForm::DELETE]);
                        
                        $dataArray = [];
                        
                        $adminCategoriesWidgetConfig = $this->adminCategoriesWidgetConfig($categoriesModelArray, $categoriesForm, $subcategoryForm);
                        $dataArray['list'] = AdminCategoriesWidget::widget($adminCategoriesWidgetConfig);
                        
                        $categoriesOptionWidgetConfig = $this->categoriesOptionWidgetConfig($categoriesModelArray);
                        $dataArray['options'] = CategoriesOptionWidget::widget($categoriesOptionWidgetConfig);
                        
                        $transaction->commit();
                        
                        return $dataArray;
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

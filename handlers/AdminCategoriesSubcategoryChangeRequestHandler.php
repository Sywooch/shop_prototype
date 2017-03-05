<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\models\SubcategoryModel;
use app\savers\ModelSaver;
use app\finders\SubcategoryIdFinder;
use app\forms\SubcategoryForm;

/**
 * Обрабатывает запрос на редактирование категории
 */
class AdminCategoriesSubcategoryChangeRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Редактирует категорию
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new SubcategoryForm(['scenario'=>SubcategoryForm::EDIT]);
            
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
                        if (empty($subcategoryModel)) {
                            throw new ErrorException($this->emptyError('subcategoryModel'));
                        }
                        
                        $subcategoryModel->scenario = SubcategoryModel::EDIT;
                        $subcategoryModel->active = $form->active ?? 0;
                        if ($subcategoryModel->validate() === false) {
                            throw new ErrorException($this->modelError($subcategoryModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$subcategoryModel
                        ]);
                        $saver->save();
                        
                        $transaction->commit();
                        
                        return true;
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

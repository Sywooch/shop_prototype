<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\models\CategoriesModel;
use app\savers\ModelSaver;
use app\finders\CategoryIdFinder;
use app\forms\CategoriesForm;

/**
 * Обрабатывает запрос на редактирование категории
 */
class AdminCategoriesCategoryChangeRequestHandler extends AbstractBaseHandler
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
            $form = new CategoriesForm(['scenario'=>CategoriesForm::EDIT]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(CategoryIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $categoriesModel = $finder->find();
                        if (empty($categoriesModel)) {
                            throw new ErrorException($this->emptyError('categoriesModel'));
                        }
                        
                        $categoriesModel->scenario = CategoriesModel::EDIT;
                        $categoriesModel->active = $form->active ?? 0;
                        if ($categoriesModel->validate() === false) {
                            throw new ErrorException($this->modelError($categoriesModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$categoriesModel
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

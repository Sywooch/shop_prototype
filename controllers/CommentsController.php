<?php

namespace app\controllers;

use yii\helpers\Url;
use yii\base\ErrorException;
use yii\db\Transaction;
use app\helpers\MappersHelper;
use app\controllers\AbstractBaseController;
use app\models\{CategoriesModel,
    CommentsModel,
    EmailsModel,
    ProductsModel,
    SubcategoryModel};

/**
 * Управляет процессом добавления комментария
 */
class CommentsController extends AbstractBaseController
{
    /**
     * Добавляет комментарий к товару
     * @return redirect
     */
    public function actionAddComment()
    {
        try {
            $commentsModel = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
            $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
            $categoriesModel = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_FORM]);
            $subcategoryModel = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_FORM]);
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM]);
            
            if (\Yii::$app->request->isPost && $commentsModel->load(\Yii::$app->request->post()) && $emailsModel->load(\Yii::$app->request->post()) && $categoriesModel->load(\Yii::$app->request->post()) && $subcategoryModel->load(\Yii::$app->request->post()) && $productsModel->load(\Yii::$app->request->post())) {
                if ($commentsModel->validate() && $emailsModel->validate() && $categoriesModel->validate() && $subcategoryModel->validate() && $productsModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if ($result = MappersHelper::getEmailsByEmail($emailsModel)) {
                            $emailsModel = $result;
                        } else {
                            if (!MappersHelper::setEmailsInsert($emailsModel)) {
                                throw new ErrorException('Ошибка при сохранении E-mail!');
                            }
                        }
                        $commentsModel->id_emails = $emailsModel->id;
                        $commentsModel->id_products = $productsModel->id;
                        if (!MappersHelper::setCommentsInsert($commentsModel)) {
                            throw new ErrorException('Не удалось обновить данные в БД!');
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                    
                    return $this->redirect(Url::to(['product-detail/index', 'categories'=>$categoriesModel->seocode, 'subcategory'=>$subcategoryModel->seocode, 'id'=>$productsModel->id]));
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

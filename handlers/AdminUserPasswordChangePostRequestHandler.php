<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\UserChangePasswordForm;
use app\models\UsersModel;
use app\widgets\AccountChangePasswordSuccessWidget;
use app\savers\ModelSaver;
use app\finders\UserIdFinder;

/**
 * Обрабатывает запрос 
 * на обновление пароля пользователя
 */
class AdminUserPasswordChangePostRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на обновление данных пользователя
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new UserChangePasswordForm(['scenario'=>UserChangePasswordForm::ADMIN_UPDATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(UserIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $rawUsersModel = $finder->find();
                        if (empty($rawUsersModel)) {
                            throw new ErrorException($this->emptyError('rawUsersModel'));
                        }
                        
                        $rawUsersModel->scenario = UsersModel::UPDATE_PASSW;
                        $rawUsersModel->password = password_hash($form->password, PASSWORD_DEFAULT);
                        if ($rawUsersModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawUsersModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawUsersModel
                        ]);
                        $saver->save();
                        
                        $response = AccountChangePasswordSuccessWidget::widget(['template'=>'account-change-password-success.twig']);
                        
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

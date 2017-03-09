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

/**
 * Обрабатывает запрос 
 * на обновление пароля пользователя
 */
class AccountChangePasswordPostRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на обновление данных пользователя
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new UserChangePasswordForm(['scenario'=>UserChangePasswordForm::CHANGE]);
            $usersModel = \Yii::$app->user->identity;
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $rawUsersModel = $usersModel;
                        $rawUsersModel->scenario = UsersModel::UPDATE_PASSW;
                        
                        $rawUsersModel->password = password_hash($form->password, PASSWORD_DEFAULT);
                        if ($rawUsersModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawUsersModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawUsersModel
                        ]);
                        $saver->save();
                        
                        $response = AccountChangePasswordSuccessWidget::widget(['template'=>'paragraph.twig']);
                        
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

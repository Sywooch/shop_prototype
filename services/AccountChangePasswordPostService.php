<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\AbstractBaseService;
use app\forms\UserChangePasswordForm;
use app\models\UsersModel;
use app\widgets\AccountChangePasswordSuccessWidget;
use app\savers\ModelSaver;

/**
 * Обновляет пароль пользователя
 */
class AccountChangePasswordPostService extends AbstractBaseService
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
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $rawUsersModel = \Yii::$app->user->identity;
                        $rawUsersModel->scenario = UsersModel::UPDATE_PASSW;
                        $rawUsersModel->password = password_hash($form->password, PASSWORD_DEFAULT);
                        if ($rawUsersModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawUsersModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawUsersModel
                        ]);
                        $saver->save();
                        
                        $transaction->commit();
                        
                        return AccountChangePasswordSuccessWidget::widget(['view'=>'account-change-password-success.twig']);
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

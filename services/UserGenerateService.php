<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetCartWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetCurrencyWidgetConfigService,
    GetPasswordGenerateEmptyWidgetConfigService,
    GetPasswordGenerateSuccessWidgetConfigService,
    GetSearchWidgetConfigService,
    GetUserInfoWidgetConfigService};
use app\finders\{UserEmailFinder,
    RecoverySessionFinder};
use app\helpers\HashHelper;
use app\savers\ModelSaver;

/**
 * Генерирует новый пароль пользователя
 */
class UserGenerateService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на поиск и обработку данных для 
     * формирования HTML формы восстановления пароля
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $key = $request->get(\Yii::$app->params['recoveryKey']);
            $email = $request->get(\Yii::$app->params['emailKey']);
            
            if (empty($key) || empty($email)) {
                throw new NotFoundHttpException($this->error404());
            }
            
            $finder = \Yii::$app->registry->get(RecoverySessionFinder::class, ['key'=>$key]);
            $recoveryModel = $finder->find();
            
            $dataArray = [];
            
            if (empty($recoveryModel) || $recoveryModel->email !== $email) {
                $service = new GetPasswordGenerateEmptyWidgetConfigService();
                $dataArray['passwordGenerateEmptyWidgetConfig'] = $service->handle();
            } else {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $finder = \Yii::$app->registry->get(UserEmailFinder::class, ['email'=>$email]);
                    $usersModel = $finder->find();
                    if (empty($usersModel)) {
                        throw new ErrorException($this->emptyError('usersModel'));
                    }
                    
                    $tempPassword = HashHelper::randomString();
                    
                    $usersModel->password = password_hash($tempPassword, PASSWORD_DEFAULT);
                    
                    $saver = new ModelSaver([
                        'model'=>$usersModel,
                    ]);
                    $saver->save();
                    
                    $service = new GetPasswordGenerateSuccessWidgetConfigService();
                    $dataArray['passwordGenerateSuccessWidgetConfig'] = $service->handle(['tempPassword'=>$tempPassword]);
                    
                    $transaction->commit();
                } catch (\Throwable $t) {
                    $transaction->rollBack();
                    throw $t;
                }
            }
            
            $service = new GetUserInfoWidgetConfigService();
            $dataArray['userInfoWidgetConfig'] = $service->handle();
            
            $service = new GetCartWidgetConfigService();
            $dataArray['cartWidgetConfig'] = $service->handle();
            
            $service = new GetCurrencyWidgetConfigService();
            $dataArray['currencyWidgetConfig'] = $service->handle();
            
            $service = new GetSearchWidgetConfigService();
            $dataArray['searchWidgetConfig'] = $service->handle($request);
            
            $service = new GetCategoriesMenuWidgetConfigService();
            $dataArray['categoriesMenuWidgetConfig'] = $service->handle();
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

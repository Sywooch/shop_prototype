<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetCategoriesMenuWidgetConfigService,
    GetCurrencyWidgetConfigService,
    GetSearchWidgetConfigService,
    GetShortCartWidgetConfigService,
    GetUserInfoWidgetConfigService};
use app\helpers\HashHelper;

/**
 * Формирует форму для удаления связи пользователя с рассылками
 */
class MailingsUnsubscribeService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на поиск и обработку данных для 
     * формирования HTML формы удаления связи пользователя с рассылками
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $inboxKey = $request->get(\Yii::$app->params['unsubscribeKey']);
            $email = $request->get(\Yii::$app->params['emailKey']);
            
            if (empty($key) || empty($email)) {
                throw new NotFoundHttpException($this->error404());
            }
            
            $key = HashHelper::createHash([$email]);
            
            if ($inboxKey !== $key) {
                
            } else {
                
            }
            
            $service = \Yii::$app->registry->get(GetUserInfoWidgetConfigService::class);
            $dataArray['userInfoWidgetConfig'] = $service->handle();
            
            $service = \Yii::$app->registry->get(GetShortCartWidgetConfigService::class);
            $dataArray['shortCartWidgetConfig'] = $service->handle();
            
            $service = \Yii::$app->registry->get(GetCurrencyWidgetConfigService::class);
            $dataArray['currencyWidgetConfig'] = $service->handle();
            
            $service = \Yii::$app->registry->get(GetSearchWidgetConfigService::class);
            $dataArray['searchWidgetConfig'] = $service->handle($request);
            
            $service = \Yii::$app->registry->get(GetCategoriesMenuWidgetConfigService::class);
            $dataArray['categoriesMenuWidgetConfig'] = $service->handle();
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

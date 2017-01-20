<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetCategoriesMenuWidgetConfigService,
    GetCurrencyWidgetConfigService,
    GetSearchWidgetConfigService,
    GetShortCartWidgetConfigService,
    GetUnsubscribeEmptyWidgetConfigService,
    GetUnsubscribeFormWidgetConfigService,
    GetUserInfoWidgetConfigService};
use app\helpers\HashHelper;
use app\finders\MailingsEmailFinder;

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
            
            if (empty($inboxKey) || empty($email)) {
                throw new NotFoundHttpException($this->error404());
            }
            
            $key = HashHelper::createHash([$email]);
            
            if ($inboxKey !== $key) {
                throw new NotFoundHttpException($this->error404());
            } else {
                if (empty((\Yii::$app->registry->get(MailingsEmailFinder::class, ['email'=>$email]))->find())) {
                    $service = \Yii::$app->registry->get(GetUnsubscribeEmptyWidgetConfigService::class);
                    $dataArray['mailingsUnsubscribeEmptyWidgetConfig'] = $service->handle($request);
                } else {
                    $service = \Yii::$app->registry->get(GetUnsubscribeFormWidgetConfigService::class);
                    $dataArray['unsubscribeFormWidgetConfig'] = $service->handle($request);
                }
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

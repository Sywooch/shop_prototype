<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    GetCartWidgetConfigService,
    GetShortCartWidgetConfigRedirectService};
use app\forms\PurchaseForm;
use app\savers\SessionArraySaver;
use app\helpers\HashHelper;
use app\finders\PurchasesSessionFinder;
use app\widgets\{CartWidget,
    ShortCartRedirectWidget};
use app\models\PurchasesModel;

/**
 * Сохраняет новую покупку в корзине
 */
class CartUpdateService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на сохранение новой покупки в корзине
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new PurchaseForm(['scenario'=>PurchaseForm::UPDATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $key = HashHelper::createCartKey();
                    
                    $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, ['key'=>$key]);
                    $purchasesCollection = $finder->find();
                    
                    $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::UPDATE]);
                    $rawPurchasesModel->quantity = $form->quantity;
                    $rawPurchasesModel->id_color = $form->id_color;
                    $rawPurchasesModel->id_size = $form->id_size;
                    $rawPurchasesModel->id_product = $form->id_product;
                    if ($rawPurchasesModel->validate() === false) {
                        throw new ErrorException($this->modelError($rawPurchasesModel->errors));
                    }
                    
                    $purchasesCollection->update($rawPurchasesModel);
                    
                    $saver = new SessionArraySaver([
                        'key'=>$key,
                        'models'=>$purchasesCollection->asArray()
                    ]);
                    $saver->save();
                    
                    $dataArray = [];
                    
                    $service = \Yii::$app->registry->get(GetCartWidgetConfigService::class);
                    $cartWidgetConfig = $service->handle();
                    $dataArray['items'] = CartWidget::widget($cartWidgetConfig);
                    
                    $service = \Yii::$app->registry->get(GetShortCartWidgetConfigRedirectService::class);
                    $shortCartRedirectWidgetConfig = $service->handle();
                    $dataArray['shortCart'] = ShortCartRedirectWidget::widget($shortCartRedirectWidgetConfig);
                    
                    return $dataArray;
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

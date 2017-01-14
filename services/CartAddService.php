<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    GetShortCartWidgetAjaxConfigService};
use app\forms\PurchaseForm;
use app\savers\SessionArraySaver;
use app\helpers\HashHelper;
use app\finders\PurchasesSessionFinder;
use app\widgets\{PurchaseSaveInfoWidget,
    ShortCartWidget};
use app\models\PurchasesModel;

/**
 * Сохраняет новую покупку в корзине
 */
class CartAddService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на сохранение новой покупки в корзине
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new PurchaseForm(['scenario'=>PurchaseForm::SAVE]);
            
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
                    
                    $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::SESSION]);
                    $rawPurchasesModel->quantity = $form->quantity;
                    $rawPurchasesModel->id_color = $form->id_color;
                    $rawPurchasesModel->id_size = $form->id_size;
                    $rawPurchasesModel->id_product = $form->id_product;
                    $rawPurchasesModel->price = $form->price;
                    if ($rawPurchasesModel->validate() === false) {
                        throw new ErrorException($this->modelError($rawPurchasesModel->errors));
                    }
                    
                    $purchasesCollection->add($rawPurchasesModel);
                    
                    $saver = new SessionArraySaver([
                        'key'=>$key,
                        'models'=>$purchasesCollection->asArray()
                    ]);
                    $saver->save();
                    
                    $dataArray = [];
                    
                    $service = \Yii::$app->registry->get(GetShortCartWidgetAjaxConfigService::class);
                    $shortCartWidgetAjaxConfig = $service->handle();
                    
                    /*$dataArray['shortCart'] = ShortCartWidget::widget($shortCartWidgetAjaxConfig);
                    $dataArray['successInfo'] = PurchaseSaveInfoWidget::widget(['view'=>'save-purchase-info.twig']);*/
                    
                    return ShortCartWidget::widget($shortCartWidgetAjaxConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

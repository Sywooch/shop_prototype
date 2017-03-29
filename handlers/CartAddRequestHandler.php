<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\PurchaseForm;
use app\savers\SessionArraySaver;
use app\helpers\HashHelper;
use app\finders\PurchasesSessionFinder;
use app\widgets\ModShortCartWidget;
use app\models\PurchasesModel;
use app\services\GetCurrentCurrencyModelService;

/**
 * Обрабатывает запрос на добавление покупки в корзину
 */
class CartAddRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
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
                    
                    $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                        'key'=>HashHelper::createCurrencyKey()
                    ]);
                    $currentCurrencyModel = $service->get();
                    if (empty($currentCurrencyModel)) {
                        throw new ErrorException($this->emptyError('currentCurrencyModel'));
                    }
                    
                    $key = HashHelper::createCartKey();
                    
                    $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                        'key'=>$key
                    ]);
                    $purchasesCollection = $finder->find();
                    if (empty($purchasesCollection)) {
                        throw new ErrorException($this->emptyError('purchasesCollection'));
                    }
                    
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
                    
                    $shortCartWidgetAjaxConfig = $this->shortCartWidgetAjaxConfig($purchasesCollection, $currentCurrencyModel);
                    return ModShortCartWidget::widget($shortCartWidgetAjaxConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

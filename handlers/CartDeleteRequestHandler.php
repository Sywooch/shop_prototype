<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\PurchaseForm;
use app\savers\SessionArraySaver;
use app\helpers\HashHelper;
use app\finders\PurchasesSessionFinder;
use app\widgets\{CartWidget,
    ShortCartRedirectWidget};
use app\models\PurchasesModel;
use app\removers\SessionRemover;
use app\services\GetCurrentCurrencyModelService;

/**
 * Обрабатывает запрос на удаление покупки
 */
class CartDeleteRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new PurchaseForm(['scenario'=>PurchaseForm::DELETE]);
            
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
                    $ordersCollection = $finder->find();
                    if (empty($ordersCollection)) {
                        throw new ErrorException($this->emptyError('ordersCollection'));
                    }
                    
                    $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::DELETE]);
                    $rawPurchasesModel->id_product = $form->id_product;
                    if ($rawPurchasesModel->validate() === false) {
                        throw new ErrorException($this->modelError($rawPurchasesModel->errors));
                    }
                    
                    $ordersCollection->delete($rawPurchasesModel);
                    
                    if ($ordersCollection->isEmpty() === true) {
                        $remover = new SessionRemover([
                            'keys'=>[$key],
                        ]);
                        $remover->remove();
                        
                        return Url::to(['/products-list/index']);
                    } else {
                        $saver = new SessionArraySaver([
                            'key'=>$key,
                            'models'=>$ordersCollection->asArray()
                        ]);
                        $saver->save();
                        
                        $updateForm = new PurchaseForm(['scenario'=>PurchaseForm::UPDATE]);
                        $deleteForm = new PurchaseForm(['scenario'=>PurchaseForm::DELETE]);
                        
                        $dataArray = [];
                        
                        $cartWidgetConfig = $this->cartWidgetConfig($ordersCollection, $currentCurrencyModel, $updateForm, $deleteForm);
                        $dataArray['items'] = CartWidget::widget($cartWidgetConfig);
                        
                        $shortCartRedirectWidgetConfig = $this->shortCartRedirectWidgetConfig($ordersCollection, $currentCurrencyModel);
                        $dataArray['shortCart'] = ShortCartRedirectWidget::widget($shortCartRedirectWidgetConfig);
                        
                        return $dataArray;
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

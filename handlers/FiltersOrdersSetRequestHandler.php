<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\forms\OrdersFiltersForm;
use app\helpers\{HashHelper,
    StringHelper};
use app\savers\SessionModelSaver;
use app\filters\OrdersFilters;

/**
 * Обрабатывает запрос, сохраняя фильтры каталога товаров
 */
class FiltersOrdersSetRequestHandler extends AbstractBaseHandler
{
    /**
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new OrdersFiltersForm(['scenario'=>OrdersFiltersForm::SAVE]);
            
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('request'));
            }
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            $model = new OrdersFilters(['scenario'=>OrdersFilters::SESSION]);
            $model->sortingType = $form->sortingType;
            $model->status = $form->status;
            $model->dateFrom = $form->dateFrom;
            $model->dateTo = $form->dateTo;
            if ($model->validate() === false) {
                throw new ErrorException($this->modelError($model->errors));
            }
            
            $saver = new SessionModelSaver([
                'key'=>HashHelper::createHash([\Yii::$app->params['ordersFilters']]),
                'model'=>$model
            ]);
            $saver->save();
            
            return StringHelper::cutPage($form->url);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

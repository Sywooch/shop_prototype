<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\forms\OrdersFiltersForm;
use app\helpers\{HashHelper,
    StringHelper};
use app\savers\SessionModelSaver;
use app\filters\OrdersFilters;

/**
 * Сохраняет фильтры каталога товаров
 */
class FiltersOrdersSetService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на сохранение товарных фильтров
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
            $model->datesInterval = $form->datesInterval;
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

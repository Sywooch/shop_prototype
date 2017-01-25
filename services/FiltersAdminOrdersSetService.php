<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\services\AbstractBaseService;
use app\forms\AdminOrdersFiltersForm;
use app\helpers\HashHelper;
use app\savers\SessionModelSaver;
use app\filters\AdminOrdersFilters;

/**
 * Сохраняет фильтры каталога товаров
 */
class FiltersAdminOrdersSetService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на сохранение товарных фильтров
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new AdminOrdersFiltersForm(['scenario'=>AdminOrdersFiltersForm::SAVE]);
            
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('request'));
            }
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            $model = new AdminOrdersFilters(['scenario'=>AdminOrdersFilters::SESSION]);
            $model->sortingType = $form->sortingType;
            $model->status = $form->status;
            if ($model->validate() === false) {
                throw new ErrorException($this->modelError($model->errors));
            }
            
            $saver = new SessionModelSaver([
                'key'=>HashHelper::createHash([\Yii::$app->params['adminOrdersFilters']]),
                'model'=>$model
            ]);
            $saver->save();
            
            return Url::to(['/admin/orders']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

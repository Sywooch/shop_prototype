<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\forms\AdminProductsFiltersForm;
use app\helpers\{HashHelper,
    StringHelper};
use app\savers\SessionModelSaver;
use app\filters\AdminProductsFilters;

/**
 * Сохраняет фильтры каталога товаров
 */
class FiltersAdminProductsSetRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на сохранение товарных фильтров
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new AdminProductsFiltersForm(['scenario'=>AdminProductsFiltersForm::SAVE]);
            
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('request'));
            }
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            $model = new AdminProductsFilters(['scenario'=>AdminProductsFilters::SESSION]);
            $model->attributes = $form->toArray();
            if ($model->validate() === false) {
                throw new ErrorException($this->modelError($model->errors));
            }
            
            $saver = new SessionModelSaver([
                'key'=>HashHelper::createHash([\Yii::$app->params['adminProductsFilters']]),
                'model'=>$model
            ]);
            $saver->save();
            
            return StringHelper::cutPage($form->url);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

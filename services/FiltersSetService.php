<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\forms\FiltersForm;
use app\helpers\{HashHelper,
    StringHelper};
use app\savers\SessionModelSaver;
use app\filters\ProductsFilters;

/**
 * Сохраняет фильтры каталога товаров
 */
class FiltersSetService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на сохранение товарных фильтров
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new FiltersForm(['scenario'=>FiltersForm::SAVE]);
            
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('request'));
            }
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            $model = new ProductsFilters(['scenario'=>ProductsFilters::SESSION]);
            $model->sortingField = $form->sortingField;
            $model->sortingType = $form->sortingType;
            $model->colors = $form->colors;
            $model->sizes = $form->sizes;
            $model->brands = $form->brands;
            if ($model->validate() === false) {
                throw new ErrorException($this->modelError($model->errors));
            }
            
            $saver = new SessionModelSaver([
                'key'=>HashHelper::createFiltersKey($form->url),
                'model'=>$model
            ]);
            $saver->save();
            
            return StringHelper::cutPage($form->url);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

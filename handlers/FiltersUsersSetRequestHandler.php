<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\forms\UsersFiltersForm;
use app\helpers\{HashHelper,
    StringHelper};
use app\savers\SessionModelSaver;
use app\filters\UsersFilters;

/**
 * Обрабатывает запрос, сохраняя фильтры каталога товаров
 */
class FiltersUsersSetRequestHandler extends AbstractBaseHandler
{
    /**
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new UsersFiltersForm(['scenario'=>UsersFiltersForm::SAVE]);
            
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('post'));
            }
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            $model = new UsersFilters(['scenario'=>UsersFilters::SESSION]);
            $model->sortingField = $form->sortingField;
            $model->sortingType = $form->sortingType;
            if ($model->validate() === false) {
                throw new ErrorException($this->modelError($model->errors));
            }
            
            $saver = new SessionModelSaver([
                'key'=>HashHelper::createHash([\Yii::$app->params['usersFilters']]),
                'model'=>$model
            ]);
            $saver->save();
            
            return StringHelper::cutPage($form->url);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

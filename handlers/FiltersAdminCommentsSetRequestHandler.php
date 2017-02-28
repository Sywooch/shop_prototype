<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\forms\AdminCommentsFiltersForm;
use app\helpers\{HashHelper,
    StringHelper};
use app\savers\SessionModelSaver;
use app\filters\CommentsFilters;

/**
 * Обрабатывает запрос, сохраняя фильтры каталога товаров
 */
class FiltersAdminCommentsSetRequestHandler extends AbstractBaseHandler
{
    /**
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new AdminCommentsFiltersForm(['scenario'=>AdminCommentsFiltersForm::SAVE]);
            
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('post'));
            }
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            $model = new CommentsFilters(['scenario'=>CommentsFilters::SESSION]);
            $model->attributes = $form->toArray();
            if ($model->validate() === false) {
                throw new ErrorException($this->modelError($model->errors));
            }
            
            $saver = new SessionModelSaver([
                'key'=>HashHelper::createHash([\Yii::$app->params['commentsFilters']]),
                'model'=>$model
            ]);
            $saver->save();
            
            return StringHelper::cutPage($form->url);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

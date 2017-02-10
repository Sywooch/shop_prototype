<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\helpers\{HashHelper,
    StringHelper};
use app\cleaners\SessionCleaner;
use app\forms\AdminProductsFiltersForm;

/**
 * Обнуляет фильтры заказов админ раздела
 */
class FiltersAdminProductsUnsetRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на обнуление фильтров заказов
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new AdminProductsFiltersForm(['scenario'=>AdminProductsFiltersForm::CLEAN]);
            
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('request'));
            }
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            $cleaner = new SessionCleaner([
                'keys'=>[HashHelper::createHash([\Yii::$app->params['adminProductsFilters']])],
            ]);
            $cleaner->clean();
            
            return StringHelper::cutPage($form->url);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

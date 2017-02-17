<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\helpers\{HashHelper,
    StringHelper};
use app\removers\SessionRemover;
use app\forms\OrdersFiltersForm;

/**
 * Обнуляет фильтры заказов админ раздела
 */
class FiltersOrdersUnsetRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на обнуление фильтров заказов
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new OrdersFiltersForm(['scenario'=>OrdersFiltersForm::CLEAN]);
            
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('request'));
            }
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            $remover = new SessionRemover([
                'keys'=>[HashHelper::createHash([\Yii::$app->params['ordersFilters']])],
            ]);
            $remover->remove();
            
            return StringHelper::cutPage($form->url);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\forms\FiltersForm;
use app\helpers\{HashHelper,
    StringHelper};
use app\removers\SessionRemover;

/**
 * Очищает фильтры каталога товаров
 */
class FiltersUnsetRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на обнуление товарных фильтров
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new FiltersForm(['scenario'=>FiltersForm::CLEAN]);
            
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('request'));
            }
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            $remover = new SessionRemover([
                'keys'=>[HashHelper::createFiltersKey($form->url)],
            ]);
            $remover->remove();
            
            return StringHelper::cutPage($form->url);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

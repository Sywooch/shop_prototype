<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\forms\FiltersForm;
use app\helpers\{HashHelper,
    StringHelper};
use app\cleaners\SessionCleaner;

/**
 * Очищает фильтры каталога товаров
 */
class FiltersCleanService extends AbstractBaseService
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
            
            $cleaner = new SessionCleaner([
                'keys'=>[HashHelper::createFiltersKey($form->url)],
            ]);
            $cleaner->clean();
            
            return StringHelper::cutPage($form->url);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

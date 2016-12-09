<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\services\ServiceInterface;
use app\forms\FiltersForm;
use app\helpers\{SessionHelper,
    StringHelper};

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class FiltersSetService extends Object implements ServiceInterface
{
    use ExceptionsTrait;
    
    /**
     * Обрабатывает запрос на сохранение товарных фильтров
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new FiltersForm(['scenario'=>FiltersForm::SAVE]);
            
            if ($form->load($request)) {
                if ($form->validate() === false) {
                    throw new ErrorException($this->modelError($form->errors));
                }
                $key = StringHelper::cutPage($form->url);
                if (!empty($key)) {
                    SessionHelper::write($key, $form->toArray());
                }
            }
            
            return $form->url;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\services\ServiceInterface;
use app\forms\FiltersForm;
use app\helpers\{SessionHelper,
    StringHelper};
use app\savers\OneSessionSaver;

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
                print_r($form);
                if ($form->validate() === false) {
                    throw new ErrorException($this->modelError($form->errors));
                }
                $key = StringHelper::cutPage($form->url);
                if (!empty($key)) {
                    $saver = new OneSessionSaver();
                    $saver->load(['key'=>$key, 'model'=>$form]);
                    $saver->save();
                }
            }
            
            return $form->url;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

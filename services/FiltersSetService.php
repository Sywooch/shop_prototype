<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\services\ServiceInterface;
use app\forms\FiltersForm;
use app\helpers\{HashHelper,
    SessionHelper,
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
                if ($form->validate() === false) {
                    throw new ErrorException($this->modelError($form->errors));
                }
                
                $cutUrl = StringHelper::cutPage($form->url);
                $key = HashHelper::createHash([$cutUrl, \Yii::$app->user->id ?? '']);
                
                if (!empty($key)) {
                    $saver = new OneSessionSaver();
                    $saver->load(['key'=>$key, 'model'=>$form]);
                    $saver->save();
                }
            }
            
            return $cutUrl;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

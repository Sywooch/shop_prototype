<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с формой поиска
 */
class SearchWidget extends AbstractBaseWidget
{
    /**
     * @var string искомая фраза
     */
    public $text;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с формой поиска
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['formId'] = 'search-form';
            $renderArray['formAction'] = Url::to(['/search']);
            $renderArray['formOptions'] = ['name'=>'search-form'];
            $renderArray['placeholder'] = \Yii::t('base', 'Search');
            $renderArray['fieldName'] = \Yii::$app->params['searchKey'];
            $renderArray['fieldSize'] = 60;
            $renderArray['text'] = $this->text ?? '';
            $renderArray['button'] = \Yii::t('base', 'Search');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

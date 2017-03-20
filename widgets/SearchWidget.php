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
    private $text;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку с формой поиска
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['formId'] = 'search-form';
            $renderArray['formAction'] = Url::to(['/search']);
            $renderArray['formOptions'] = ['name'=>'search-form'];
            $renderArray['placeholder'] = \Yii::t('base', 'Search');
            $renderArray['fieldName'] = \Yii::$app->params['searchKey'];
            //$renderArray['fieldSize'] = 60;
            $renderArray['text'] = $this->text ?? '';
            $renderArray['button'] = \Yii::t('base', 'Search');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ahfpe свойству SearchWidget::text
     * @param string $template
     */
    public function setText(string $text)
    {
        try {
            $this->text = $text;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству SearchWidget::template
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        try {
            $this->template = $template;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

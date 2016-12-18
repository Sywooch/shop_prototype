<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\SearchForm;

/**
 * Формирует HTML строку с формой поиска
 */
class SearchWidget extends AbstractBaseWidget
{
    /**
     * @var SearchForm
     */
    private $form;
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
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['formModel'] = $this->form;
            $renderArray['formId'] = 'search-form';
            $renderArray['formAction'] = Url::to(['/search']);
            $renderArray['formOptions'] = ['name'=>'search-form'];
            $renderArray['placeholder'] = \Yii::t('base', 'Search');
            $renderArray['button'] = \Yii::t('base', 'Search');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает SearchForm свойству SearchWidget::form
     * @param SearchForm $form
     */
    public function setForm(SearchForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

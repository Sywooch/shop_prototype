<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminBrandsWidget extends AbstractBaseWidget
{
    /**
     * @var array BrandsModel
     */
    private $brands;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var AbstractBaseForm
     */
    private $brandsForm;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку с данными
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->brands)) {
                throw new ErrorException($this->emptyError('brands'));
            }
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->brandsForm)) {
                throw new ErrorException($this->emptyError('brandsForm'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            $brandsArray = [];
            foreach ($this->brands as $brand) {
                $set = [];
                $set['brand'] = $brand->brand;
                
                $brandsForm = clone $this->brandsForm;
                $set['modelForm'] = \Yii::configure($brandsForm, ['id'=>$brand->id]);
                $set['formId'] = sprintf('admin-brand-delete-form-%d', $brand->id);
                
                $set['ajaxValidation'] = false;
                $set['validateOnSubmit'] = false;
                $set['validateOnChange'] = false;
                $set['validateOnBlur'] = false;
                $set['validateOnType'] = false;
                
                $set['formAction'] = Url::to(['/admin/brand-delete']);
                $set['button'] = \Yii::t('base', 'Delete');
                
                $brandsArray[] = $set;
            }
            
            ArrayHelper::multisort($brandsArray, 'brand', SORT_ASC);
            $renderArray['brands'] = $brandsArray;
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminBrandsWidget::brands
     * @param array $brands
     */
    public function setBrands(array $brands)
    {
        try {
            $this->brands = $brands;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству AdminBrandsWidget::header
     * @param string $header
     */
    public function setHeader(string $header)
    {
        try {
            $this->header = $header;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству AdminBrandsWidget::brandsForm
     * @param AbstractBaseForm $brandsForm
     */
    public function setBrandsForm(AbstractBaseForm $brandsForm)
    {
        try {
            $this->brandsForm = $brandsForm;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству AdminBrandsWidget::template
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

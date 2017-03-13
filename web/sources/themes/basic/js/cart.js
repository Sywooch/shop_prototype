$(function() {
    
    function Send() {
        AbstractSendForm.call(this);
    };
    
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    /* 
     * Запрашивает форму оформления заказа, 
    * обновляет информацию и состоянии
    */
    $('#cart-сheckout-ajax-link').on('click', 'input:submit', function(event) {
        (new Send()).htmlSend(event, 'div.cart-checkout');
        event.preventDefault();
    });
    
    /* 
     * Отправляет форму с данными для обновления информации о заказе,
    * обновляет информацию и состоянии
    * Отправляет форму с данными для удаления товара из заказе, 
    * обновляет информацию и состоянии или редирект, если товар был единственным в заказе
    */
    $('div.cart-items').on('click', 'form[id^="update-product-form"] > input:submit, form[id^="delete-product-form"] > input:submit', function(event) {
        (new Send()).htmlArrayRedirectSend(event, 'div.cart-items', 'div.shortCart', 'items', 'shortCart');
        event.preventDefault();
    });
    
    /*
     * Добавляет поля для ввода пароля с целью регистрации пользователя
     */
    $('div.cart-checkout').on('change', 'input[name="CustomerInfoForm[create]"]', function(event) {
        $('div.cart-create-user').toggleClass('disable');
    });
    
    /*
     * Добавляет поле для отметки необходимости обновить информацию
     */
    $('div.cart-checkout').on('focusin', 'input:text', function(event) {
        $('div.cart-change-user').removeClass('disable');
    });
    
    /*
     * Отправляет форму с данными для оформления заказа,
     * редирект при успешном выполнении скрипта
     */
    $('div.cart-checkout').on('click', '#cart-сheckout-ajax-form > input:submit', function(event) {
        (new Send()).redirectSend(event);
        event.preventDefault();
    });
});

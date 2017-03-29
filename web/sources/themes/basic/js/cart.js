$(function() {
    
    function Send() {
        AbstractSendForm.call(this);
    };
    
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    var send = new Send();
    
    /* 
     * Запрашивает форму оформления заказа, 
    * обновляет информацию и состоянии
    */
    $('#cart-сheckout-ajax-link').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.cart-checkout');
        event.preventDefault();
    });
    
    /* 
     * Отправляет форму с данными для обновления информации о заказе,
    * обновляет информацию и состоянии
    * Отправляет форму с данными для удаления товара из заказа, 
    * обновляет информацию и состоянии или редирект, если товар был единственным в заказе
    */
    $('.cart-items').on('click', 'form[id^="update-product-form"] > input[type="submit"], form[id^="delete-product-form"] > input[type="submit"]', function(event) {
        send.htmlArrayRedirectSend(event, '.cart-items', '#short-cart', 'items', 'shortCart');
        event.preventDefault();
    });
    
    var cartCheckout = $('.cart-checkout');
    
    /*
     * Добавляет поля для ввода пароля с целью регистрации пользователя
     */
    cartCheckout.on('change', 'input[name="CustomerInfoForm[create]"]', function(event) {
        $('.cart-create-user').toggleClass('disable');
    });
    
    /*
     * Добавляет поле для отметки необходимости обновить информацию
     */
    cartCheckout.on('focusin', 'input[type="text"]', function(event) {
        $('.cart-change-user').removeClass('disable');
    });
    
    /*
     * Отправляет форму с данными для оформления заказа,
     * редирект при успешном выполнении скрипта
     */
    cartCheckout.on('click', '#cart-сheckout-ajax-form > input[type="submit"]', function(event) {
        send.redirectSend(event);
        event.preventDefault();
    });
});

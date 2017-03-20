$(function() {
    
    function Send() {
        AbstractSendForm.call(this);
    };
    Send.prototype = Object.create(AbstractSendForm.prototype);
    var send = new Send();
    
    
    var setCurrency = new SetCurrency();
    
    /* 
     * Отправляет форму с данными для очистки корзины, 
    * обновляет информацию и состоянии
    */
    $('.shortCart').on('click', '#clean-cart-form > input[type="submit"]', function(event) {
        send.htmlSend(event, '.shortCart');
        event.preventDefault();
    });
    
    /* 
     * Управляет видимостью списка категорий товаров
    */
    $('#categories-menu-container').on('click', '.category-button > span', function(event) {
        var li = $(event.target).closest('li');
        li.find('.subcategory-menu').toggleClass('disable');
        li.toggleClass('bottom-line');
    });
    
    /* 
     * Управляет видимостью списка доступных валют
    */
    $('#currency').on('click', '.currency-button > span', function(event) {
        var li = $(event.target).closest('li');
        li.find('.currency-not-active').toggleClass('disable');
        li.toggleClass('bottom-line');
    });
    
    /* 
     * Отправляет запрос на замену текущей валюты
    */
    $('#currency').on('click', '.currency-not-active span', function(event) {
        setCurrency.redirectSend(event);
        event.preventDefault();
    });
    
});

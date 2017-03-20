$(function() {
    
    function Send() {
        AbstractSendForm.call(this);
    };
    
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    var send = new Send();
    
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
    
});

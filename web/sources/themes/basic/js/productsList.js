$(function() {
    
    var send = new AbstractSendForm();
    
    /* 
     * Отправляет форму с данными для очистки корзины, 
    * обновляет информацию и состоянии
    */
    $('.shortCart').on('click', '#clean-cart-form > input[type="submit"]', function(event) {
        send.htmlSend(event, '.shortCart');
        event.preventDefault();
    });
    
    /* 
     * Инициирует отправку формы logout
    */
    $('#user-info').on('click', '.logout', function(event) {
        $('#user-logout-form').submit();
    });
    
});

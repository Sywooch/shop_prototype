$(function() {
    
    function Send() {
        AbstractSendForm.call(this);
    };
    
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    var send = new Send();
    
    /* Отправляет форму с данными для очистки корзины, 
    * обновляет информацию и состоянии
    */
    $('.shortCart').on('click', '#clean-cart-form > input[type="submit"]', function(event) {
        send.htmlSend(event, '.shortCart');
        event.preventDefault();
    });
    
    /* Отправляет форму с данными для добавления товара в корзину, 
    * обновляет информацию и состоянии
    */
    $('#purchase-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.shortCart');
        event.preventDefault();
    });
    
    /* Отправляет форму с комментарием, 
    * обновляет информацию и состоянии
    */
    $('#comment-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.comment-success', true, false, true);
        event.preventDefault();
    });
});

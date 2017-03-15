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
    
    /* Отправляет форму с данными о выбранных подписках, 
    * обновляет информацию и состоянии
    */
    $('#mailings-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.mailings-success', false, false, true);
        event.preventDefault();
    });
});

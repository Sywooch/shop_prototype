$(function() {
    
    function Send() {
        AbstractSendForm.call(this);
    };
    
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    var send = new Send();
    
    /* Отправляет форму с данными для очистки корзины, 
    * обновляет информацию и состоянии
    */
    $('div.shortCart').on('click', '#clean-cart-form > input:submit', function(event) {
        send.htmlSend(event, 'div.shortCart');
        event.preventDefault();
    });
    
    /* Отправляет форму с данными о выбранных подписках, 
    * обновляет информацию и состоянии
    */
    $('#mailings-form').on('click', 'input:submit', function(event) {
        send.htmlTimeoutSend(event, 'div.mailings-success');
        event.preventDefault();
    });
});

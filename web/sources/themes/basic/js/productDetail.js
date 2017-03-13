$(function() {
    
    function Send() {
        AbstractSendForm.call(this);
    };
    
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    /* Отправляет форму с данными для очистки корзины, 
    * обновляет информацию и состоянии
    */
    $('div.shortCart').on('click', '#clean-cart-form > input:submit', function(event) {
        (new Send()).htmlSend(event, 'div.shortCart');
        event.preventDefault();
    });
    
    /* Отправляет форму с данными для добавления товара в корзину, 
    * обновляет информацию и состоянии
    */
    $('#purchase-form').on('click', 'input:submit', function(event) {
        (new Send()).htmlSend(event, 'div.shortCart');
        event.preventDefault();
    });
    
    /* Отправляет форму с комментарием, 
    * обновляет информацию и состоянии
    */
    $('#comment-form').on('click', 'input:submit', function(event) {
        (new Send()).htmlTimeoutSend(event, 'div.comment-success', true);
        event.preventDefault();
    });
});

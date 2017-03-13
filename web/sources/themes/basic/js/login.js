$(function() {
    
    function Send() 
    {
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
    
    /* Отправляет форму с данными для аутентификации, 
    * выполняет редирект при успешеом выполнении скрипта
    */
    $('#login-form').on('click', 'input:submit', function(event) {
        (new Send()).redirectSend(event);
        event.preventDefault();
    });
});

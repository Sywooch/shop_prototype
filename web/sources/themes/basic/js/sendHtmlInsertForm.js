$(function() {
    
    function SendHtmlInsertForm() {
        AbstractSendForm.call(this);
    };
    
    SendHtmlInsertForm.prototype = Object.create(AbstractSendForm.prototype);
    
    /* Отправляет форму с данными заказа для добавления в корзину, 
     * обновляет информацию и состоянии
     */
    $('#purchase-form').on('click', 'input:submit', function(event) {
        (new SendHtmlInsertForm()).htmlSend(event, 'div.shortCart');
        event.preventDefault();
    });
    
    /* Отправляет форму с данными для очистки корзины, 
    обновляет информацию и состоянии
    */
    $('div.shortCart').on('click', '#clean-cart-form > input:submit', function(event) {
        (new SendHtmlInsertForm()).htmlSend(event, 'div.shortCart');
        event.preventDefault();
    });
    
    // Отправляет форму регистрации пользователя, обновляет информацию и состоянии
    $('#registration-form').on('click', 'input:submit', function(event) {
        (new SendHtmlInsertForm()).htmlSend(event, 'div.registration');
        event.preventDefault();
    });
});

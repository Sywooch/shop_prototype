$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    var send = new Send();
    
    /*
     * Отправялет запрос на добавление валюты
     */
    $('#currency-create-form').on('click', 'input:submit', function(event) {
        send.htmlSend(event, 'div.admin-currency', true);
        event.preventDefault();
    });
    
    /*
     * Отправляет запрос на удаление валюты
     */
    $('div.admin-currency').on('click', 'input:submit', function(event) {
        send.htmlSend(event, 'div.admin-currency');
        event.preventDefault();
    });
});

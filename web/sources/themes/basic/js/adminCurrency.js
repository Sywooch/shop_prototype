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
        send.htmlSend(event, 'div.admin-currency', false, true);
        event.preventDefault();
    });
    
    /*
     * Отправляет запрос на изменение базовой валюты
     */
    $('div.admin-currency').on('change', 'input:checkbox', function(event) {
        $('form[id^="admin-currency-delete-form"] > input:submit').attr('disabled', true);
        send.htmlSend(event, 'div.admin-currency', false, true);
        event.preventDefault();
    });
});

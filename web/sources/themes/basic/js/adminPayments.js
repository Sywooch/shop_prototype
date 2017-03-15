$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    var send = new Send();
    
    /*
     * Отправляет запрос на создание нового способа оплаты
     */
    $('#payment-create-form').on('click', 'input:submit', function(event) {
        send.htmlSend(event, 'div.admin-payments', true);
        event.preventDefault();
    });
    
    /*
     * Отправляет запрос на удаление способа оплаты
     */
     $('div.admin-payments').on('click', 'form[id^="admin-payment-delete-form"] > input:submit', function(event) {
        send.htmlSend(event, 'div.admin-payments', false, true);
        event.preventDefault();
    });
    
    /*
     * Запрашивает форму редактирования
     */
    $('div.admin-payments').on('click', 'form[id^="admin-payment-get-form"] > input:submit', function(event) {
        send.htmlLiToggleSend(event, 'div.admin-payment-previous-data');
        event.preventDefault();
    });
    
    /*
     * Отменяет форму редактирования
     */
    $('div.admin-payments').on('click', ':submit[name="cancel"]', function(event) {
        send.removeForm(event, 'div.admin-payment-previous-data', 'div.admin-payment-edit-form');
        event.preventDefault();
    });
    
    /*
     * Отправляет форму редактирования с данными
     */
    $('div.admin-payments').on('click', ':submit[name="send"]', function(event) {
        send.htmlLiSend(event);
        event.preventDefault();
    });
});

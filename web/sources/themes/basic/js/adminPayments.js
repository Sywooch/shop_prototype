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
    $('#payment-create-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.admin-payments', true);
        event.preventDefault();
    });
    
    var adminPayments = $('.admin-payments');
    
    /*
     * Отправляет запрос на удаление способа оплаты
     */
    adminPayments.on('click', 'form[id^="admin-payment-delete-form"] > input[type="submit"]', function(event) {
        send.htmlSend(event, '.admin-payments', false, true);
        event.preventDefault();
    });
    
    /*
     * Запрашивает форму редактирования
     */
    adminPayments.on('click', 'form[id^="admin-payment-get-form"] > input[type="submit"]', function(event) {
        send.htmlLiToggleSend(event, '.admin-payment-previous-data');
        event.preventDefault();
    });
    
    /*
     * Отменяет форму редактирования
     */
    adminPayments.on('click', 'input[type="submit"][name="cancel"]', function(event) {
        send.removeForm(event, '.admin-payment-previous-data', '.admin-payment-edit-form');
        event.preventDefault();
    });
    
    /*
     * Отправляет форму редактирования с данными
     */
    adminPayments.on('click', 'input[type="submit"][name="send"]', function(event) {
        send.htmlLiSend(event);
        event.preventDefault();
    });
});

$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    var send = new Send();
    
    /*
     * Запрос на создание новой формы доставки
     */
    $('#delivery-create-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.admin-deliveries', true);
        event.preventDefault();
    });
    
    var adminDeliveries = $('.admin-deliveries');
    
    /*
     * Запрос удаления формы доставки
     */
    adminDeliveries.on('click', 'form[id^="admin-delivery-delete-form"] > input[type="submit"]', function(event) {
        send.htmlSend(event, '.admin-deliveries', false, true);
        event.preventDefault();
    });
    
    /*
     * Запрос формы редактирования
     */
    adminDeliveries.on('click', 'form[id^="admin-delivery-get-form"] > input[type="submit"]', function(event) {
        send.htmlLiToggleSend(event, '.admin-delivery-previous-data');
        event.preventDefault();
    });
    
    /*
     * Закрыть форму редактирования
     */
    adminDeliveries.on('click', 'input[type="submit"][name="cancel"]', function(event) {
        send.removeForm(event, '.admin-delivery-previous-data', '.admin-delivery-edit-form');
        event.preventDefault();
    });
    
    /*
     * Запрос изменения формы доставки
     */
    adminDeliveries.on('click', 'input[type="submit"][name="send"]', function(event) {
        send.htmlLiSend(event);
        event.preventDefault();
    });
});

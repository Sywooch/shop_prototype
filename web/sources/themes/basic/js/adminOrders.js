$(function() {
    
    function AdminCalendar() {
        Calendar.call(this);
    };
    AdminCalendar.prototype = Object.create(Calendar.prototype);
    
    var adminCalendar = new AdminCalendar();
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    var send = new Send();
    
    /* 
     * Запрашивает календарь
    */
    $('div.orders-filters').on('click', '[class^="calendar-href"]', function(event) {
        adminCalendar.send(event, 'p.calendar-place');
        event.preventDefault();
    });
    
    /*
     * Отправляет запрос формы редактирования заказа
     */
    $('li').on('click', 'form[id^="admin-order-detail-get-form"] > input:submit', function(event) {
        send.htmlLiToggleSend(event, 'div.admin-order-previous-data');
        event.preventDefault();
    });
    
    /*
     * Удаляет форму редактирования
     */
    $('li').on('click', 'form[id^="admin-order-detail-send-form"] > input:submit[name="cancel"]', function(event) {
        send.removeForm(event, 'div.admin-order-previous-data', 'div.admin-order-change-form');
        event.preventDefault();
    });
    
    /*
     * Отправляет форму
     */
    $('li').on('click', 'form[id^="admin-order-detail-send-form-"] > input:submit[name="send"]', function(event) {
        send.htmlLiSend(event);
        event.preventDefault();
    });
    
    /*
     * Запрашивает ссылку на CSV представление данных
     */
    $('#admin-scv-orders-form').on('click', 'input:submit', function(event) {
        send.htmlSend(event, 'p.csv-success');
        event.preventDefault();
    });
    
});

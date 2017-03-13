$(function() {
    
    function AdminCalendar() {
        Calendar.call(this);
    };
    AdminCalendar.prototype = Object.create(Calendar.prototype);
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    /* 
     * Запрашивает календарь
    */
    $('div.orders-filters').on('click', '[class^="calendar-href"]', function(event) {
        (new AdminCalendar()).send(event, 'p.calendar-place');
        event.preventDefault();
    });
    
    /*
     * Отправляет запрос формы редактирования заказа
     */
    $('li').on('click', 'form[id^="admin-order-detail-get-form"] > input:submit', function(event) {
        (new Send()).htmlLiToggleSend(event, 'div.admin-order-previous-data');
        event.preventDefault();
    });
    
    /*
     * Удаляет форму редактирования, возвращает информацию о заказе
     */
    $('li').on('click', 'form[id^="admin-order-detail-send-form"] > input:submit[name="cancel"]', function(event) {
        var li = $(event.target).closest('li');
        li.find('div.admin-order-previous-data').toggleClass('disable');
        li.find('div.admin-order-change-form').remove();
        event.preventDefault();
    });
    
    /*
     * Отправляет форму
     */
    $('li').on('click', 'form[id^="admin-order-detail-send-form-"] > input:submit[name="send"]', function(event) {
        (new Send()).htmlLiSend(event);
        event.preventDefault();
    });
    
    /*
     * Запрашивает ссылку на CSV представление данных
     */
    $('#admin-scv-orders-form').on('click', 'input:submit', function(event) {
        (new Send()).htmlSend(event, 'p.csv-success');
        event.preventDefault();
    });
    
});

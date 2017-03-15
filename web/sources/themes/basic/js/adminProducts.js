$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    var send = new Send();
    
    function SubcategorySend()
    {
        GetSubcategory.call(this);
    }
    SubcategorySend.prototype = Object.create(GetSubcategory.prototype);
    var subcategorySend = new SubcategorySend();
    
    function SendFile()
    {
        AbstractSendFile.call(this);
    }
    SendFile.prototype = Object.create(AbstractSendFile.prototype);
    var sendFile = new SendFile();
    
    /*
     * Получает подкатегории для выбранной категории
     */
    $('#admin-products-filters-form').on('change', '#adminproductsfiltersform-category', function(event) {
        subcategorySend.send(event, '#adminproductsfiltersform-subcategory');
        event.preventDefault();
    });
    
    var li = $('.admin-products').find('li');
    
    /*
     * Запрашивает форму редактирования товара
     */
    li.on('click', 'form[id^="admin-product-detail-get-form"] > input[type="submit"]', function(event) {
        send.htmlLiToggleSend(event, '.admin-product-previous-data');
        event.preventDefault();
    });
    
    /*
     * Удаляет товар
     */
    li.on('click', 'form[id^="admin-product-detail-delete-form"] > input[type="submit"]', function(event) {
        var name = $(event.target).closest('.admin-product-previous-data').find('a').text();
        var result = confirm('Delete ' + name + '?');
        if (result == true) {
            send.htmlLiToggleSend(event, '.admin-product-previous-data');
        }
        event.preventDefault();
    });
    
    /*
     * Отменят редактирование товара
     */
    li.on('click', 'input[type="submit"][name="cancel"]', function(event) {
        send.removeForm(event, '.admin-product-previous-data', '.admin-product-change-form');
        event.preventDefault();
    });
    
    /*
     * Отправляет форму с обновленными данными
     */
    li.on('click', 'input[type="submit"][name="send"]', function(event) {
        sendFile.htmlLiSend(event);
        event.preventDefault();
    });
    
    /*
     * Запрашивает данные в формате CSV
     */
    $('#admin-scv-products-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.csv-success');
        event.preventDefault();
    });
    
});

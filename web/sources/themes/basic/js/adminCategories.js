$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    var send = new Send();
    
    /* 
     * Делает не активными первые строки в выпадающем списке
    */
    send.firstOptionDisable();
    
    /* 
     * Отправляет запрос на создание категории,
     * обновляет данные на странице
    */
    $('#category-create-form').on('click', ':submit', function(event) {
        send.htmlArraySend(event, 'div.product-categories', '#subcategoryform-id_category', 'list', 'options', true, true);
        event.preventDefault();
    });
    
    /*
     * Отправляет запрос на создание подкатегории,
     * обновляет данные на странице
     */
    $('#subcategory-create-form').on('click', ':submit', function(event) {
        send.htmlSend(event, 'div.product-categories', true);
        event.preventDefault();
    });
    
    /* 
     * Отправляет запрос на удаление категории,
     * обновляет данные на странице
    */
    $('div.product-categories').on('click', 'form[id^="admin-category-delete-form"] > input:submit', function(event) {
        send.htmlArraySend(event, 'div.product-categories', '#subcategoryform-id_category', 'list', 'options', true, false, true);
        event.preventDefault();
    });
    
    /* 
     * Отправляет запрос на удаление подкатегории,
     * обновляет данные на странице
    */
    $('div.product-categories').on('click', 'form[id^="admin-subcategory-delete-form"] > input:submit', function(event) {
        send.htmlSend(event, 'div.product-categories', false, true);
        event.preventDefault();
    });
    
    /*
     * Отправляет запрос на изменение статуса
     */
    $('div.product-categories').on('change', 'input:checkbox', function(event) {
        send.emptyResponseSend(event);
        event.preventDefault();
    });
    
});

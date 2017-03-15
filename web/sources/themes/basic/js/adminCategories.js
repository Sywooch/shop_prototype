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
    $('#category-create-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlArraySend(event, '.product-categories', '#subcategoryform-id_category', 'list', 'options', true, true);
        event.preventDefault();
    });
    
    /*
     * Отправляет запрос на создание подкатегории,
     * обновляет данные на странице
     */
    $('#subcategory-create-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.product-categories', true);
        event.preventDefault();
    });
    
    var productCategories = $('.product-categories');
    
    /* 
     * Отправляет запрос на удаление категории,
     * обновляет данные на странице
    */
    productCategories.on('click', 'form[id^="admin-category-delete-form"] > input[type="submit"]', function(event) {
        send.htmlArraySend(event, '.product-categories', '#subcategoryform-id_category', 'list', 'options', true, false, true);
        event.preventDefault();
    });
    
    /* 
     * Отправляет запрос на удаление подкатегории,
     * обновляет данные на странице
    */
    productCategories.on('click', 'form[id^="admin-subcategory-delete-form"] > input[type="submit"]', function(event) {
        send.htmlSend(event, '.product-categories', false, true);
        event.preventDefault();
    });
    
    /*
     * Отправляет запрос на изменение статуса
     */
    productCategories.on('change', 'input[type="checkbox"]', function(event) {
        send.emptyResponseSend(event);
        event.preventDefault();
    });
    
});

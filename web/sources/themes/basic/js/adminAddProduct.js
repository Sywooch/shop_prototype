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
     * Делает не активными первые строки в выпадающем списке
    */
    send.firstOptionDisable();
    
    /*
     * Запрашивает подкатегории
     */
    $('body').on('change', '#adminproductform-id_category', function(event) {
        subcategorySend.send(event, '#adminproductform-id_subcategory', true, true);
        event.preventDefault();
    });
    
    /*
     * Отправляет форму с данными товара
     */
    $('.admin-add-product-form').on('click', 'input[type="submit"]', function(event) {
        sendFile.htmlArraySend(event, '.add-product-success', '.admin-add-product-form', 'successText', 'form', false, true, false, true);
        event.preventDefault();
    });
});

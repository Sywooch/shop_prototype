$(function() {
    
    function SendCleanCart() {
        AbstractSendForm.call(this);
    };
    
    SendCleanCart.prototype = Object.create(AbstractSendForm.prototype);
    
    $('#purchase-form, div.shortCart').on('click', 'input:submit', function(event) {
        (new SendCleanCart()).htmlSend(event, 'div.shortCart');
        event.preventDefault();
    });
    
});

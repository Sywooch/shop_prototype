$(function() {
    
    function SendCleanCart() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            if (typeof data == 'string') {
                $('#cart').html(data);
            }
        };
    };
    
    $('#cart').on('click', '#clean-cart-form > input[type="submit"]', function(event) {
        (new SendCleanCart()).send(event);
        event.preventDefault();
    });
    
});

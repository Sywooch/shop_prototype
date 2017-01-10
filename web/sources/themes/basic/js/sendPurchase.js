$(function() {
    
    function SendPurchase() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            if (typeof data == 'object' && data.length != 0) {
                $('#cart').html(data['cartInfo']);
                alert(data['successInfo']);
            }
        };
    };
    
    $('#purchase-form').on('click', 'input[type="submit"]', function(event) {
        (new SendPurchase()).send(event);
        event.preventDefault();
    });
    
});

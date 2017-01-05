$(function() {
    
    function SendPurchase() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR) {
            if (data.length != 0) {
                $('#cart').html(data['cartInfo']);
                alert(data['successInfo']);
            }
        };
    };
    
    $('#add-to-cart-form').find('input[type="submit"]').click(function(event) {
        (new SendPurchase()).send(event);
        event.preventDefault();
    });
    
});

$(function() {
    
    function SendCurrency() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            if (data.length != 0) {
                $('#cart').html(data['cartInfo']);
            }
        };
    };
    
    $('#set-currency-form').find('input[type="submit"]').click(function(event) {
        (new SendCurrency()).send(event);
        event.preventDefault();
    });
    
});

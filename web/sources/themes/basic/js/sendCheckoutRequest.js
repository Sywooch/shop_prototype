$(function() {
    
    function SendCheckoutRequest() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            if (typeof data == 'string') {
                $('div.cart-checkout').html(data);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('div.cart-checkout').on('click', '#cart-сheckout-ajax-link > input[type="submit"], #cart-сheckout-ajax-form > input[type="submit"]', function(event) {
        (new SendCheckoutRequest()).send(event);
        event.preventDefault();
    });
    
});

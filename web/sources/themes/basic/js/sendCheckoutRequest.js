$(function() {
    
    function SendCheckoutRequest() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            if (typeof data == 'string') {
                $('div.cart-checkout').html(data);
            }
        };
    };
    
    $('div.cart-checkout').on('click', '#cart-сheckout-ajax-link > input[type="submit"]', function(event) {
        (new SendCheckoutRequest()).send(event);
        event.preventDefault();
    });
    
    function SendCheckoutForm() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                alert('Ваш заказ успешно отправлен!');
                window.location.replace(data);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('div.cart-checkout').on('click', '#cart-сheckout-ajax-form > input[type="submit"]', function(event) {
        (new SendCheckoutForm()).send(event);
        event.preventDefault();
    });
    
     $('div.cart-checkout').on('change', 'input[name="CustomerInfoForm[create]"]', function(event) {
        $('div.cart-create-user').toggleClass('disable');
    });
    
});

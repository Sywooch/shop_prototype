$(function() {
    
    function SendCartItemsUpdate() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            if (typeof data == 'object' && data.length != 0) {
                $('div.cart-items').html(data['items']);
                $('div.shortCart').each(function(index, elm) {
                    $(elm).html(data['shortCart']);
                });
            }
        };
    };
    
    $('div.cart-items').on('click', 'form[name^="update-product-form"] > input[type="submit"]', function(event) {
        (new SendCartItemsUpdate()).send(event);
        event.preventDefault();
    });
    
    function SendCartItemsDelete() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            if (typeof data == 'string') {
                window.location.replace(data);
            } else if (typeof data == 'object' && data.length != 0) {
                $('div.cart-items').html(data['items']);
                $('div.shortCart').each(function(index, elm) {
                    $(elm).html(data['shortCart']);
                });
            }
        };
    };
    
    $('div.cart-items').on('click', 'form[name^="delete-product-form"] > input[type="submit"]', function(event) {
        (new SendCartItemsDelete()).send(event);
        event.preventDefault();
    });
    
});

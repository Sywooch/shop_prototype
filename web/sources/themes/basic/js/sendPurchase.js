$(function() {
    
    function SendPurchase() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            /*if (typeof data == 'object' && data.length != 0) {
                $('div.shortCart').html(data['shortCart']);
                alert(data['successInfo']);
            }*/
            if (typeof data == 'string') {
                alert('Товар успешно добавлен в корзину!');
                $('div.shortCart').html(data);
            }
        };
    };
    
    $('#purchase-form').find('input[type="submit"]').on('click', function(event) {
        (new SendPurchase()).send(event);
        event.preventDefault();
    });
    
});

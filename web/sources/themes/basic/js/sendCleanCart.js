$(function() {
    
    function SendCleanCart() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                $('div.shortCart').html(data);
            }
        };
    };
    
    $('div.shortCart').on('click', '#clean-cart-form > input[type="submit"]', function(event) {
        (new SendCleanCart()).send(event);
        event.preventDefault();
    });
    
});

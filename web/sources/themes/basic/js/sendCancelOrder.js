$(function() {
    
    function SendCancelOrder() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            if (typeof data == 'string') {
                self.form.closest('li').find('span.account-order-status').html(data);
                self.form.remove();
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('[class^="account-order-"]').find('input[type="submit"]').on('click', function(event) {
        (new SendCancelOrder()).send(event);
        event.preventDefault();
    });
    
});

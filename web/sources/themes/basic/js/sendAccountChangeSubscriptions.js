$(function() {
    
    function SendAccountChangeSubscriptions() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            self.form.find('input').blur();
            if (typeof data == 'object' && data.length != 0) {
                $('div.account-unsubscribe').html(data.unsubscribe);
                $('div.account-subscribe').html(data.subscribe);
            }
        };
    };
    
    $('div.account-unsubscribe, div.account-subscribe').find('input[type="submit"]').on('click', function(event) {
        (new SendAccountChangeSubscriptions()).send(event);
        event.preventDefault();
    });
    
});

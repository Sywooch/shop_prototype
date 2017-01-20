$(function() {
    
    function SendMailingsUnsubscribe() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                $('div.unsubscribe').html(data);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('div.unsubscribe').on('click', '#unsubscribe-form > input[type="submit"]', function(event) {
        (new SendMailingsUnsubscribe()).send(event);
        event.preventDefault();
    });
    
});

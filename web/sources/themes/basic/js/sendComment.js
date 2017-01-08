$(function() {
    
    function SendComment() {
        var self = this;
        self.infoDiv = $('div.comment-success');
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            if (typeof data == 'string') {
                self.form.find('input[type="text"], textarea').val('');
                self.infoDiv.html(data);
                setTimeout(timeoutRemove, 5000);
            } else if (typeof data == 'object') {
                for (var key in data) {
                    $('#' + key).closest('.form-group').find('.help-block').text(data[key]);
                }
            }
        };
        function timeoutRemove()
        {
            self.infoDiv.empty();
        }
    };
    
    $('#comment-form').find('input[type="submit"]').click(function(event) {
        (new SendComment()).send(event);
        event.preventDefault();
    });
    
});

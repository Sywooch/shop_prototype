$(function() {
    
    function SendComment() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR) {
            if (typeof data == 'string') {
                $('#add-comment-form').find('input[type="text"], textarea').val('');
                $('#add-comment-form').find('.help-block').text('');
                alert(data);
            } else if (typeof data == 'object') {
                for (var key in data) {
                    $('#' + key).closest('.form-group').find('.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('#add-comment-form').find('input[type="submit"]').click(function(event) {
        (new SendComment()).send(event);
        event.preventDefault();
        event.stopPropagation();
    });
    
});

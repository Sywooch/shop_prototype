$(function() {
    
    function SendAdminProductDetailChange() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                self.form.closest('li').html(data);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
        self.send = function(event) {
            self.form = $(event.target).closest('form');
            self.formData = new FormData(self.form[0]);
            self.url = self.form.attr('action');
            self.token = self.form.find('input[name="_csrf"]').val();
            $.ajax({
                'headers': {'X-CSRF-Token': self.token},
                'url': self.url,
                'type': 'POST',
                'data': self.formData,
                'dataType': 'json',
                'processData': false,
                'contentType': false,
                'success': self.success,
                'error': self.error,
            });
        };
    };
    
    $('li').on('click', 'form[id^="admin-product-detail-send-form-"] > input[type="submit"][name="send"]', function(event) {
        (new SendAdminProductDetailChange()).send(event);
        event.preventDefault();
    });
    
    $('li').on('click', 'form[id^="admin-product-detail-send-form-"] > input[type="submit"][name="cancel"]', function(event) {
        var li = $(event.target).closest('li');
        li.find('div.admin-product-previous-data').toggleClass('disable');
        li.find('div.admin-product-change-form').remove();
        event.preventDefault();
    });
    
});

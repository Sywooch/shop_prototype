$(function() {
    
    function SendAddProductForm() {
        var self = this;
        self.infoDiv = $('div.add-product-success');
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                self.form.find('input[type="text"], textarea').val('');
                self.infoDiv.html(data);
                setTimeout(timeoutRemove, 5000);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
        function timeoutRemove()
        {
            self.infoDiv.empty();
        }
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
    
    $('div.admin-add-product-form').on('click', 'input[type="submit"]', function(event) {
        (new SendAddProductForm()).send(event);
        event.preventDefault();
    });
    
});

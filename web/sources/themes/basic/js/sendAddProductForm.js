$(function() {
    
    function SendAddProductForm() {
        var self = this;
        self.infoDiv = $('div.add-product-success');
        self.formDiv = $('div.admin-add-product-form');
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'object' && data.length != 0) {
                if ('successText' in data && 'form' in data) {
                    self.infoDiv.html(data.successText);
                    self.formDiv.html(data.form);
                    setTimeout(timeoutRemove, 5000);
                    setDisable();
                } else {
                    for (var key in data) {
                        $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                    }
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
        function timeoutRemove()
        {
            self.infoDiv.empty();
        }
        function setDisable()
        {
            $('select[data-disabled]').each(function(index, domElement) {
                $(domElement).find('option:first').attr('disabled', true);
            });
        }
    };
    
    $('div.admin-add-product-form').on('click', 'input[type="submit"]', function(event) {
        (new SendAddProductForm()).send(event);
        event.preventDefault();
    });
    
});

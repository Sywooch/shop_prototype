function AbstractSendForm() {
    var self = this;
    self.form;
    self.url;
    self.token;
    
    self.error = function(jqXHR, status, errorThrown) {
        alert(status + ' ' + jqXHR.responseText);
    };
    
    self.send = function(event) {
        self.form = $(event.target).closest('form');
        self.url = self.form.attr('action');
        self.token = self.form.find('input[name="_csrf"]').val();
        $.ajax({
            'headers': {'X-CSRF-Token': self.token},
            'url': self.url,
            'type': 'POST',
            'data': self.form.serialize(),
            'dataType': 'json',
            'success': self.success,
            'error': self.error,
        });
    };
};

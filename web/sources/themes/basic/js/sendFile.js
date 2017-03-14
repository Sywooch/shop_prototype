function AbstractSendFile() {
    AbstractSendForm.call(this);
};

AbstractSendFile.prototype = Object.create(AbstractSendForm.prototype);

AbstractSendFile.prototype.baseSend =  function(event, success) {
    try {
        this.form = $(event.target).closest('form');
        this.url = this.form.attr('action');
        this.token = this.form.find('input[name="_csrf"]').val();
        var formData = new FormData(this.form[0]);
        
        $.ajax({
            'headers': {'X-CSRF-Token': this.token},
            'url': this.url,
            'type': 'POST',
            'data': formData,
            'dataType': 'json',
            'processData': false,
            'contentType': false,
            'success': success.bind(this),
            'error': this.error,
        });
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

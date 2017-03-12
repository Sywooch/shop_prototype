function AbstractSendForm() 
{
    this.form;
    this.url;
    this.token;
};

AbstractSendForm.prototype.error = function(jqXHR, status, errorThrown) {
    console.log(status + ' ' + jqXHR.responseText);
};

AbstractSendForm.prototype.baseFormSend = function(event) {
    try {
        this.form = $(event.target).closest('form');
        this.url = this.form.attr('action');
        this.token = this.form.find('input[name="_csrf"]').val();
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.redirectSend = function(event) {
    try {
        this.baseFormSend(event);
        
        $.ajax({
            'headers': {'X-CSRF-Token': this.token},
            'url': this.url,
            'type': 'POST',
            'data': this.form.serialize(),
            'dataType': 'json',
            'success': successRedirect,
            'error': this.error,
            'context':this.form
        });
        
        function successRedirect(data, status, jqXHR) {
            try {
                Helpers.call(this);
                this.cleanHelpBlock(this);
                
                if (typeof data == 'string') {
                    window.location.replace(data);
                } else if (typeof data == 'object' && data.length != 0) {
                    this.addErrors(data);
                }
            } catch (e) {
                console.log(e.name + ': ' + e.message);
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.htmlSend = function(event, container) {
    try {
        this.baseFormSend(event);
        
        $.ajax({
            'headers': {'X-CSRF-Token': this.token},
            'url': this.url,
            'type': 'POST',
            'data': this.form.serialize(),
            'dataType': 'json',
            'success': successHtml,
            'error': this.error,
            'context':this.form
        });
        
        function successHtml(data, status, jqXHR) {
            try {
                Helpers.call(this);
                this.cleanHelpBlock(this);
                
                if (typeof data == 'string') {
                    $(container).html(data);
                }
            } catch (e) {
                console.log(e.name + ': ' + e.message);
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};









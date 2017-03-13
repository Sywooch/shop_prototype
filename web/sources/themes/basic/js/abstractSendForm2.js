function AbstractSendForm() 
{
    this.form;
    this.url;
    this.token;
};

AbstractSendForm.prototype.error = function(jqXHR, status, errorThrown) {
    console.log(status + ' ' + jqXHR.responseText);
};

AbstractSendForm.prototype.baseSend =  function(event) {
    try {
        this.form = $(event.target).closest('form');
        this.url = this.form.attr('action');
        this.token = this.form.find('input[name="_csrf"]').val();
        
        var config = {
            'headers': {'X-CSRF-Token': this.token},
            'url': this.url,
            'type': 'POST',
            'data': this.form.serialize(),
            'dataType': 'json',
            'error': this.error,
            'context':this.form
        };
        
        return config;
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.redirectSend = function(event) {
    try {
        var config = this.baseSend(event);
        config.success = success;
        $.ajax(config);
        
        function success(data, status, jqXHR) {
            Helpers.call(this);
            this.cleanHelpBlock(this);
            this.loseFocus(this);
            
            if (typeof data == 'string') {
                window.location.replace(data);
            } else if (typeof data == 'object' && data.length != 0) {
                this.addErrors(data);
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.htmlSend = function(event, container) {
    try {
        var config = this.baseSend(event);
        config.success = success;
        $.ajax(config);
        
        function success(data, status, jqXHR) {
            Helpers.call(this);
            this.cleanHelpBlock(this);
            this.loseFocus(this);
            
            if (typeof data == 'string') {
                $(container).html(data);
            } else if (typeof data == 'object' && data.length != 0) {
                this.addErrors(data);
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.htmlTimeoutSend = function(event, container) {
    try {
        var config = this.baseSend(event);
        config.success = success;
        $.ajax(config);
        
        function success(data, status, jqXHR) {
            Helpers.call(this);
            this.cleanHelpBlock(this);
            this.loseFocus(this);
            
            if (typeof data == 'string') {
                this.cleanFields(this);
                $(container).html(data);
                this.timeoutRemove(container, 5000);
            } else if (typeof data == 'object' && data.length != 0) {
                this.addErrors(data);
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.htmlArrayRedirectSend = function(event, container1, container2, item1, item2) {
    try {
        var config = this.baseSend(event);
        config.success = success;
        $.ajax(config);
        
        function success(data, status, jqXHR) {
            Helpers.call(this);
            this.cleanHelpBlock(this);
            this.loseFocus(this);
            
            if (typeof data == 'string') {
                window.location.replace(data);
            } else if (typeof data == 'object' && data.length != 0) {
                if (data.hasOwnProperty(item1) && data.hasOwnProperty(item2)) {
                    $(container1).html(data[item1]);
                    $(container2).each(function(index, elm) {
                        $(elm).html(data[item2]);
                    });
                } else {
                    this.addErrors(data);
                }
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};









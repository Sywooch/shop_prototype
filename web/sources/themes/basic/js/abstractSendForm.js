function AbstractSendForm() 
{
    this.form;
    this.url;
    this.token;
};

AbstractSendForm.prototype.error = function(jqXHR, status, errorThrown) {
    console.log(status + ' ' + jqXHR.responseText);
};

AbstractSendForm.prototype.formInit = function(event) {
    try {
        this.form = $(event.target).closest('form');
        this.url = this.form.attr('action');
        this.token = this.form.find('input[name="_csrf"]').val();
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.baseFormAjaxConfig = function() {
    try {
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
        this.formInit(event);
        var config = this.baseFormAjaxConfig();
        config.success = success;
        $.ajax(config);
        
        function success(data, status, jqXHR) {
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
        this.formInit(event);
        var config = this.baseFormAjaxConfig();
        config.success = success;
        $.ajax(config);
        
        function success(data, status, jqXHR) {
            try {
                Helpers.call(this);
                this.cleanHelpBlock(this);
                
                if (typeof data == 'string') {
                    $(container).html(data);
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

AbstractSendForm.prototype.htmlTimeoutSend = function(event, container) {
    try {
        this.formInit(event);
        var config = this.baseFormAjaxConfig();
        config.success = success;
        $.ajax(config);
        
        function success(data, status, jqXHR) {
            try {
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
            } catch (e) {
                console.log(e.name + ': ' + e.message);
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.htmlArrayRedirectSend = function(event, container1, container2, item1, item2) {
    try {
        this.formInit(event);
        var config = this.baseFormAjaxConfig();
        config.success = success;
        $.ajax(config);
        
        function success(data, status, jqXHR) {
            try {
                Helpers.call(this);
                this.cleanHelpBlock(this);
                
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
            } catch (e) {
                console.log(e.name + ': ' + e.message);
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};









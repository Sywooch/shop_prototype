function AbstractSendForm() 
{
    Helpers.call(this);
    this.form;
    this.url;
    this.token;
};

AbstractSendForm.prototype.beforeSuccess = function() {
    this.cleanHelpBlock(this.form);
    this.loseFocus(this.form);
};

AbstractSendForm.prototype.error = function(jqXHR, status, errorThrown) {
    console.log(status + ' ' + jqXHR.responseText);
};

AbstractSendForm.prototype.baseSend =  function(event, success) {
    try {
        this.form = $(event.target).closest('form');
        this.url = this.form.attr('action');
        this.token = this.form.find('input[name="_csrf"]').val();
        
        $.ajax({
            'headers': {'X-CSRF-Token': this.token},
            'url': this.url,
            'type': 'POST',
            'data': this.form.serialize(),
            'dataType': 'json',
            'error': this.error,
            'success': success.bind(this)
        });
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.redirectSend = function(event) {
    try {
        var config = this.baseSend(event, success);
        
        function success(data, status, jqXHR) {
            this.beforeSuccess();
            
            if (typeof data == 'string') {
                window.location.replace(data);
            } else if (typeof data == 'object' && data.length != 0) {
                this.addErrors(data);
            } else {
                throw Error('Invalid data type!');
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.htmlSend = function(event, container) {
    try {
        var config = this.baseSend(event, success);
        
        function success(data, status, jqXHR) {
            this.beforeSuccess();
            
            if (typeof data == 'string') {
                $(container).html(data);
            } else if (typeof data == 'object' && data.length != 0) {
                this.addErrors(data);
            } else {
                throw Error('Invalid data type!');
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.htmlTimeoutSend = function(event, container, cleanFields=false) {
    try {
        var config = this.baseSend(event, success);
        
        function success(data, status, jqXHR) {
            this.beforeSuccess();
            
            if (typeof data == 'string') {
                if (cleanFields) {
                    this.cleanFields(this.form);
                }
                $(container).html(data);
                this.timeoutRemove(container, 5000);
            } else if (typeof data == 'object' && data.length != 0) {
                this.addErrors(data);
            } else {
                throw Error('Invalid data type!');
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.htmlArrayRedirectSend = function(event, container1, container2, item1, item2) {
    try {
        var config = this.baseSend(event, success);
        
        function success(data, status, jqXHR) {
            this.beforeSuccess();
            
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
            } else {
                throw Error('Invalid data type!');
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.htmlLiRemoveSend = function(event, container) {
    try {
        var config = this.baseSend(event, success);
        
        function success(data, status, jqXHR) {
            this.beforeSuccess();
            
            if (typeof data == 'string') {
                this.form.closest('li').find(container).html(data);
                this.form.remove();
            } else if (typeof data == 'object' && data.length != 0) {
                this.addErrors(data);
            } else {
                throw Error('Invalid data type!');
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.htmlArraySend = function(event, container1, container2, item1, item2) {
    try {
        var config = this.baseSend(event, success);
        
        function success(data, status, jqXHR) {
            this.beforeSuccess();
            
            if (typeof data == 'object' && data.length != 0) {
                if (data.hasOwnProperty(item1) && data.hasOwnProperty(item2)) {
                    $(container1).html(data[item1]);
                    $(container2).html(data[item2]);
                } else {
                    this.addErrors(data);
                }
            } else {
                throw Error('Invalid data type!');
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.htmlLiToggleSend = function(event, container) {
    try {
        var config = this.baseSend(event, success);
        
        function success(data, status, jqXHR) {
            this.beforeSuccess();
            
            if (typeof data == 'string') {
                this.form.closest('li').find(container).toggleClass('disable');
                this.form.closest('li').append(data);
            } else if (typeof data == 'object' && data.length != 0) {
                this.addErrors(data);
            } else {
                throw Error('Invalid data type!');
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};

AbstractSendForm.prototype.htmlLiSend = function(event, container) {
    try {
        var config = this.baseSend(event, success);
        
        function success(data, status, jqXHR) {
            this.beforeSuccess();
            
            if (typeof data == 'string') {
                this.form.closest('li').html(data);
            } else if (typeof data == 'object' && data.length != 0) {
                this.addErrors(data);
            } else {
                throw Error('Invalid data type!');
            }
        };
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};







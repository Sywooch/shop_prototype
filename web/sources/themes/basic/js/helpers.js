function Helpers()
{
    this.cleanHelpBlock = function(form) {
        try {
            form.find('div.help-block').html('');
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    this.addErrors = function(data) {
        try {
            for (var key in data) {
                $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
            }
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    this.loseFocus = function(form) {
        try {
            form.find('input, textarea').blur();
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    this.cleanFields = function(form) {
        try {
            form.find('input:text, textarea').val('');
            form.find('input:checkbox').prop('checked', false);
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    this.timeoutRemove = function(container, time) {
        try {
            setTimeout(function() {
                $(container).empty();
            }, time);
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
}

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
}

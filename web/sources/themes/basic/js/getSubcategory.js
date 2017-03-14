function GetSubcategory() {
    Helpers.call(this);
    this.target;
    this.url;
    this.token;
    
    this.send = function(event, container, disable=false, filler=false) {
        try {
            this.target = $(event.target);
            this.url = this.target.data('href');
            this.token = this.target.closest('form').find('input[name="_csrf"]').val();
            
            $.ajax({
                'headers': {'X-CSRF-Token': this.token},
                'url': this.url,
                'type': 'POST',
                'data': {category: this.target.val()},
                'dataType': 'json',
                'error': this.error,
                'success': success.bind(this),
            });
            
            function success(data, status, jqXHR) {
                if (typeof data == 'string') {
                    $(container).html(data);
                    if (filler == true) {
                        $(container).find('option:first').text('------------------------');
                    }
                    if (disable == true) {
                        this.firstOptionDisable();
                    }
                } else {
                    throw Error('Invalid data type!');
                }
            };
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
};

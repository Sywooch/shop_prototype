jQuery(function() {
    
    function SendRequest() {
        var self = this;
        this.form;
        this.url;
        this.token = $('meta[name="csrf-token"]').attr('content');
        function success(data, status, jqXHR) {
            alert(data);
        };
        function error(jqXHR, status, errorThrown) {
            alert(jqXHR.responseText);
        };
        this.send = function(event) {
            self.form = $(event.target).closest('form');
            self.url = $(event.target).closest('form').attr('action');
            $.ajax({
                'headers': {'X-CSRF-Token': self.token},
                'url': self.url,
                'type': 'POST',
                'data': $(self.form).serialize(),
                'dataType': 'json',
                'success': success,
                'error': error,
            });
        };
    };
    
    $('form :submit').click(function(event) {
        //alert($(event.target).closest('form').attr('action'));
        (new SendRequest()).send(event);
        return false;
    });
    
});

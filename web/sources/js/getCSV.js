jQuery(function() {
    
    function SendRequest() {
        var self = this;
        self.form;
        self.url;
        self.token = $('meta[name="csrf-token"]').attr('content');
        function success(data, status, jqXHR) {
            if (data.productsFile) {
                $('.productsFile').html('Скачать <a href="/sources/csv/' + data.productsFile + '">' + data.productsFile + '</a>');
            } else {
                $('.productsFile').html('Данные удовлетворяющие вашему запросу не найдены!');
            }
        };
        function error(jqXHR, status, errorThrown) {
            alert(jqXHR.responseText);
        };
        self.send = function(event) {
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
        (new SendRequest()).send(event);
        return false;
    });
    
});

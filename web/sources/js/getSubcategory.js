jQuery(function() {
    
    function SendRequest() {
        var self = this;
        self.formID;
        self.value;
        self.token = $('meta[name="csrf-token"]').attr('content');
        function success(data, status, jqXHR) {
            $('form#' + self.formID + ' select#productsmodel-id_subcategory').empty();
            $('form#' + self.formID + ' select#productsmodel-id_subcategory').append('<option>------------------------</option>');
            if (data) {
                for (var i in data) {
                    var option = $('<option></option>').val(i).html(data[i]);
                    $('form#' + self.formID + ' select#productsmodel-id_subcategory').append(option);
                }
            }
            $('form#' + self.formID + ' select#productsmodel-id_subcategory > option:first-child').attr('disabled', true);
        };
        function error(jqXHR, status, errorThrown) {
            alert(jqXHR.responseText);
        };
        self.send = function(event) {
            self.value = $(event.target).val();
            self.formID = $(event.target).closest('form').attr('id');
            if (!self.value) {
                alert('You must choose subcategory!');
            }
            $.ajax({
                'headers': {'X-CSRF-Token': self.token},
                'url': '/get-subcategory-ajax',
                'type': 'POST',
                'data': {'categoriesId':self.value},
                'dataType': 'json',
                'success': success,
                'error': error,
            });
        };
    };
    
    $('select#productsmodel-id_categories').change(function(event) {
        (new SendRequest()).send(event);
    });
    
});

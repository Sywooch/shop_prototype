function SendRequestAbstract() {
    var self = this;
    self._formID;
    self._value;
    self._url;
    self._token = $('form').find('input[name="_csrf"]').val();
    self._allText = '';
    
    self.success = function(data, status, jqXHR) {
        self.clean();
        $('#' + self._formID).find('#productsmodel-id_subcategory').empty();
        $('#' + self._formID).find('#productsmodel-id_subcategory').append('<option>' + self._allText + '</option>');
        if (data) {
            var formField = $('#' + self._formID).find('#productsmodel-id_subcategory');
            for (var i in data) {
                var option = $('<option></option>').val(i).html(data[i]);
                formField.append(option);
            }
        }
    };
    
    self.error = function(jqXHR, status, errorThrown) {
        alert(jqXHR.responseText);
    };
    
    self.clean = function () {
        $('#' + self._formID).find('#productsmodel-id_subcategory').empty();
        $('#' + self._formID).find('#productsmodel-id_subcategory').append('<option>' + self._allText + '</option>');
    };
    
    self.send = function(event) {
        self._value = $(event.target).val();
        self._formID = $(event.target).closest('form').attr('id');
        if (self._value) {
            $.ajax({
                'headers': {'X-CSRF-Token': self._token},
                'url': self._url,
                'type': 'POST',
                'data': {'categoryId':self._value},
                'dataType': 'json',
                'success': self.success,
                'error': self.error,
            });
        } else {
            self.clean();
        }
    };
};

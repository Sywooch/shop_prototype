function SendRequestAbstract() {
    var self = this;
    self._target;
    self._formID;
    self._formField;
    self._value;
    self._url;
    self._token = $('form').find('input[name="_csrf"]').val();
    self._allText = '';
    
    self.success = function(data, status, jqXHR) {
        self.clean();
        for (var i in data) {
            var option = $('<option></option>').val(i).html(data[i]);
            self._formField.append(option);
        }
    };
    
    self.error = function(jqXHR, status, errorThrown) {
        alert(status + ' ' + jqXHR.responseText);
    };
    
    self.clean = function () {
        self._formField.empty();
        self._formField.append('<option>' + self._allText + '</option>');
    };
    
    self.send = function(event) {
        self._target = $(event.target);
        self._value = self._target.val();
        self._url = self._target.data('href');
        self._allText = self._target.data('filler');
        self._formID = self._target.closest('form').attr('id');
        self._formField = $('#' + self._formID).find('#productsmodel-id_subcategory');
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

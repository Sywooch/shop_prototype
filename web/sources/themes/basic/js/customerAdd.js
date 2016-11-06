jQuery(function() {
    
    $('#customer-form').find('input[name^=NamesModel], input[name^=SurnamesModel], input[name^=EmailsModel], input[name^=PhonesModel], input[name^=AddressModel], input[name^=CitiesModel], input[name^=CountriesModel], input[name^=PostcodesModel]').change(function(event) {
        $('div.customer-change').removeClass('disable');
    });
    
    if ($('#customer-form').find('input[name=dataChange]').attr('checked') == 'checked') {
        $('div.customer-change').removeClass('disable');
    }
    
    if ($('#customer-form').find('input[name=createAccount]').attr('checked') == 'checked') {
        $('div.customer-password').removeClass('disable');
    }
    
    $('div.customer-create-account').change(function(event) {
        $('div.customer-password').toggleClass('disable');
    });
    
});

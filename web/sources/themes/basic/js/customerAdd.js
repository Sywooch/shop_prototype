jQuery(function() {
    
    $('#customer-form').find('input[name^=UsersModel], input[name^=EmailsModel], input[name^=PhonesModel], input[name^=AddressModel]').change(function(event) {
        $('div.customer-change').removeClass('disable');
    });
    
    $('div.customer-create-account').change(function(event) {
        $('div.customer-password').toggleClass('disable');
    });
    
});

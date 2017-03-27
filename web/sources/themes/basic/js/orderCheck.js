function OrderCheck()
{
    //this.counter;
    this.container;
    //this.count;
    
    this.run = function() {
        try {
            this.container = $('#product-detail');
            //this.counter = this.container.find('.products-filters-quantity').find('.cifra');
            //this.count = this.counter.text();
            
            this.container.on('click', '.products-filters-item', function(event) {
                var target = $(event.target);
                var ul = target.closest('ul');
                
                ul.find('.products-filters-item').removeClass('checked');
                target.toggleClass('checked');
                
                var formItem = ul.data('form-item');
                var itemId = target.closest('li').data('id');
                
                var field = $('#' + formItem);
                field.find('option').removeAttr('selected');
                field.find('option[value="' + itemId + '"]').attr('selected', 1);
                
                if ($('.products-filters-colors').find('.products-filters-item').hasClass('checked') && $('.products-filters-sizes').find('.products-filters-item').hasClass('checked')) {
                    $('.order-button').addClass('order-button-active');
                }
            });
            
            this.container.on('click', '.plus', this, function(event) {
                var counter = event.data.container.find('.products-filters-quantity').find('.cifra');
                var count = counter.text();
                counter.text(++count);
                console.log(count);
                $('#purchaseform-quantity').val(count);
            });
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    this.quantity = function() {
        try {
            this.counter = $('#product-detail').find('.products-filters-quantity').find('.cifra');
            this.count = this.counter.text();
            this.container = $('#product-detail').find('.products-filters-quantity');
            
            this.container.on('click', '.minus', this, function(event) {
                console.log('FFF');
                if (parseInt(event.data.count) > 1) {
                    event.data.counter.text(--event.data.count);
                    $('#purchaseform-quantity').val(event.data.count);
                };
            });
            
            this.container.on('click', '.plus', this, function(event) {
                event.data.counter.text(++event.data.count);
                $('#purchaseform-quantity').val(event.data.count);
            });
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
};

 (new OrderCheck()).run();

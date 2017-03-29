function OrderCheck()
{
    this.container;
    
    this.run = function() {
        try {
            this.container = $('#product-detail');
            
            this.container.on('click', '.products-filters-item', this, function(event) {
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
                    event.data.container.on('click', '.order-button', event.data, function(event) {
                        $('#purchase-form').find('input[type="submit"]').click();
                        $('.products-filters-item').removeClass('checked');
                        $('.order-button').removeClass('order-button-active');
                        event.data.container.off('click', '.order-button');
                    });
                }
            });
            
            this.container.on('click', '.plus, .minus', this, function(event) {
                var counter = event.data.container.find('.products-filters-quantity').find('.cifra');
                var count = parseInt(counter.text());
                var target = $(event.target);
                if (target.hasClass('plus')) {
                    counter.text(++count);
                } else {
                    if (count > 1) {
                        counter.text(--count);
                    }
                }
                $('#purchaseform-quantity').val(count);
            });
            
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
};

 (new OrderCheck()).run();

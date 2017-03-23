function FiltersCheck()
{
    this.run = function() {
        $('#header').on('click', '.products-filters-item', function(event) {
            var target = $(event.target);
            var ul = target.closest('ul');
            
            if (ul.hasClass('products-filters-sorting-field')) {
                ul.find('.products-filters-item').removeClass('checked');
            }
            target.toggleClass('checked');
            
            var formItem = ul.data('form-item');
            var itemId = target.closest('li').data('id');
            
            if (formItem == 'filtersform-sortingfield') {
                var field = $('#filtersform-sortingfield');
                field.find('option').removeAttr('selected');
                field.find('option[value="' + itemId + '"]').attr('selected', 1);
            } else {
                var field = $('#' + formItem).find('input[value="' + itemId + '"]');
                if (field.attr('checked') == 'checked') {
                    field.removeAttr('checked');
                } else {
                    field.attr('checked', 1);
                }
            }
        });
        
        $('#header').on('click', '#filters-apply', function(event) {
            $('#products-filters-form').submit();
        });
        
        $('#header').on('click', '#filters-cancel', function(event) {
            $('#products-filters-clean').submit();
        });
    };
}

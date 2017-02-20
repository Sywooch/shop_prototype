$(function() {
    
    $('select[data-disabled]').each(function(index, domElement) {
        $(domElement).find('option:first').attr('disabled', true);
    });
    
});

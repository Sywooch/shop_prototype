$(function() {
    
    var send = new AbstractSendForm();
    var setCurrency = new SetCurrency();
    var filtersCheck = new FiltersCheck();
    
    /* 
     * Отправляет форму с данными для очистки корзины, 
    * обновляет информацию и состоянии
    */
    $('.shortCart').on('click', '#clean-cart-form > input[type="submit"]', function(event) {
        send.htmlSend(event, '.shortCart');
        event.preventDefault();
    });
    
    /* 
     * Управляет видимостью списка категорий товаров
    */
    $('#categories-menu-container').on('click', '.category-button', function(event) {
        var target = $(event.target);
        target.closest('li').find('.subcategory-menu').toggleClass('disable');
        target.toggleClass('bottom-line');
    });
    
    /* 
     * Управляет видимостью списка доступных валют
    */
    $('#currency').on('click', '.currency-button', function(event) {
        var target = $(event.target);
        target.closest('li').find('.currency-not-active').toggleClass('disable');
        target.toggleClass('bottom-line');
    });
    
    /* 
     * Отправляет запрос на замену текущей валюты
    */
    $('#currency').on('click', '.currency-not-active span', function(event) {
        setCurrency.redirectSend(event);
        event.preventDefault();
    });
    
    /* 
     * Инициирует отправку формы logout
    */
    $('#user-info').on('click', '.logout', function(event) {
        $('#user-logout-form').submit();
    });
    
    $('#categories-menu-container').on('click', '.filters-visible', function(event) {
        var filters = $('.filters-group');
        $('.header-left').append(filters);
        filters.toggleClass('disable');
    });
    
    /*
     * Помечает фильтр как выбранный
     */
    filtersCheck.run();
    
});

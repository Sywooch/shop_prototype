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
    
    /* 
    * Управляет видимостью списка категорий товаров
    */
    /*$('#categories-menu-container').on('click', '.category-button', this, function(event) {
        var target = $(event.target);
        var items = target.siblings('.subcategory-menu');
        //$('#main').prepend(items);
        items.toggleClass('disable');
        target.toggleClass('bottom-line');
    });*/
    
    /*
     * Помечает фильтр как выбранный
     */
    filtersCheck.run();
    
    function MoveToSidebar()
    {
        this.sidebar;
        this.container;
        
        this.run = function() {
            this.sidebar = $('#sidebar');
            this.container = $('#categories-menu-container');
            
            var subcategory = $('.subcategory-menu');
            var filters = $('.filters-group');
            var string = '';
            
            string += this.toString(subcategory);
            string += this.toString(filters);
            
            this.sidebar.html(string);
            //this.setEvents();
            this.current();
        };
        
        this.toString = function(object) {
            var string = '';
            
            object.each(function(index, elm) {
                string += elm.outerHTML;
                $(elm).remove();
            });
            
            return string;
        };
        
        this.setEvents = function() {
            /* 
            * Управляет видимостью списка категорий товаров
            */
            /*this.container.on('click', '.category-button', this, function(event) {
                var target = $(event.target);
                var category = target.text();
                event.data.cleaner();
                event.data.sidebar.children('.subcategory-menu[data-category="' + category + '"]').toggleClass('disable');
                target.toggleClass('bottom-line');
            });*/
            
            /*
            * Управляет видимостью фильтров
            */
            /*this.container.on('click', '.filters-visible', this, function(event) {
                event.data.sidebar.children('.filters-group').toggleClass('disable');
                $(event.target).toggleClass('bottom-line');
            });*/
        };
        
        this.current = function() {
            var container = this.sidebar.find('.active');
            if (container.length != 0) {
                var parent = container.closest('ul');
                var text = parent.data('category');
                parent.removeClass('disable');
                var buttons = this.container.find('.category-button');
                buttons.each(function(index, elm) {
                    var elm = $(elm);
                    if (elm.text() == text) {
                        elm.addClass('bottom-line');
                    }
                });
            }
        };
        
    };
    
    (new MoveToSidebar()).run();
    
});

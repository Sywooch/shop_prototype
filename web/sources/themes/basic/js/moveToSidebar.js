function MoveToSidebar()
{
    Helpers.call(this);
    
    this.sidebar;
    this.container;
    
    this.run = function() {
        try {
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
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    this.setEvents = function() {
        try {
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
            this.container.on('click', '.filters-visible', this, function(event) {
                event.data.sidebar.children('.filters-group').toggleClass('disable');
                $(event.target).toggleClass('bottom-line');
            });
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    this.current = function() {
        try {
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
            } else {
                $('#categories-menu-container').children('.categories-menu').children('li').first().children('.category-button').addClass('bottom-line');
            }
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
};

(new MoveToSidebar()).run();

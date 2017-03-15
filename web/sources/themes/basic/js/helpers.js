function Helpers()
{
    /* 
     * Очищает информацию об ошибках валидации полей
     * @param объект iQuery form обернутая форма
     */
    this.cleanHelpBlock = function(form) {
        try {
            form.find('.help-block').html('');
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    /* 
     * Добавляет на страницу информацию об ошибках валидации полей
     * @param array data массив данных
     */
    this.addErrors = function(data) {
        try {
            for (var key in data) {
                $('#' + key).closest('.form-group').find('.help-block').text(data[key]);
            }
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    /* 
     * Добавляет на страницу информацию об ошибках валидации полей
     * @param array data массив данных
     */
    this.addClosestErrors = function(data) {
        try {
            for (var key in data) {
                this.form.find('.help-block').text(data[key]);
            }
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    /* 
     * Убирает фокус с полей формы
     * @param объект iQuery form обернутая форма
     */
    this.loseFocus = function(form) {
        try {
            form.find('input, textarea').blur();
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    /* 
     * Очищает поля формы от введенных данных
     * @param объект iQuery form обернутая форма
     */
    this.cleanFields = function(form) {
        try {
            form.find('input[type="text"], input[type="password"], input[type="number"], textarea').val('');
            form.find('input[type="checkbox"]').prop('checked', false);
            form.find('select').prop('selectedIndex', 0);
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    /* 
     * Удаляет содержимое у переданного элемента через указанный промежуток времени
     * @param string container имя элемента, который будет очищен
     * @param int time время, через которое он будет очищен
     */
    this.timeoutRemove = function(container, time) {
        try {
            setTimeout(function() {
                $(container).empty();
            }, time);
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    /* 
     * Делает не активными первые строки в выпадающем списке
     */
    this.firstOptionDisable = function() {
        try {
            $('select[data-disabled]').each(function(index, domElement) {
                $(domElement).find('option').first().attr('disabled', true);
            });
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    /*
     * Закрывает форму редактирования
     */
    this.removeForm = function(event, container1, container2) {
        try {
            var li = $(event.target).closest('li');
            li.find(container1).toggleClass('disable');
            li.find(container2).remove();
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
}

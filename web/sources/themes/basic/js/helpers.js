function Helpers()
{
    /* Очищает информацию об ошибках валидации полей
     * @param объект iQuery form обернутая форма
     */
    this.cleanHelpBlock = function(form) {
        try {
            form.find('div.help-block').html('');
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    /* Добавляет на страницу информацию об ошибках валидации полей
     * @param array data массив данных
     */
    this.addErrors = function(data) {
        try {
            for (var key in data) {
                $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
            }
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    /* Убирает фокус с полей формы
     * @param объект iQuery form обернутая форма
     */
    this.loseFocus = function(form) {
        try {
            form.find('input, textarea').blur();
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    /* Очищает поля формы от введенных данных
     * @param объект iQuery form обернутая форма
     */
    this.cleanFields = function(form) {
        try {
            form.find('input:text, input:password, textarea').val('');
            form.find('input:checkbox').prop('checked', false);
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    /* Удаляет содержимое у переданного элемента через указанный промежуток времени
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
}

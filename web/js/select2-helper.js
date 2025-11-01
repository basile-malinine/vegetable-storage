/**
 * Настраивает поиск в Select2 строго от начала строки
 * Подключение в PHP:
 *
 * 'pluginOptions' => [
 *     'matcher' => new JsExpression('matchStart'),
 *     'dropdownParent' => '#modal', // необходимо указать id контекста (в данном случае id модального окна)
 * ],
 *
 */

function matchStart(params, data) {
    if ($.trim(params.term) === '') {
        return data;
    }
    if (typeof data.text === 'undefined') {
        return null;
    }
    let test = data.text.toUpperCase().startsWith(params.term.toUpperCase());

    return test ? data : null;
}

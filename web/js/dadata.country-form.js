const token = '3a202d94848db5bf4d7e51ce26885ad0c9440860';

const $name = $('#country-name');
const $alfa2 = $('#country-alfa2');
const $fullName = $('#country-full_name');

function join(arr /*, separator */) {
    let separator = arguments.length > 1 ? arguments[1] : ', ';
    return arr.filter((n) => n).join(separator);
}

function makeCountryString(country) {
    return join([country.name_short, country.name]);
}

function formatResultCountry(value, currentValue, suggestion, options) {
    let newValue = makeCountryString(suggestion.data) || value;
    suggestion.value = newValue;
    return sgsCountry.formatResult(newValue, currentValue, suggestion, options);
}

function formatSelectedCountry(suggestion) {
    let addressValue = suggestion.data.name_short;
    suggestion.value = addressValue;
    return addressValue;
}

let sgsCountry = $name.suggestions({
    token: token,
    type: 'COUNTRY',
    minChars: 2,  // Символ, с которого включаются подсказки
    count: 10,  // Кол-во подсказок в списке
    deferRequestBy: 500,  // Период ожидания перед отправкой запроса
    bounds: 'country',
    formatResult: formatResultCountry,
    formatSelected: formatSelectedCountry,
    onSelect: (sgs) => {
        let alfa2 = sgs.data.alfa2 ? sgs.data.alfa2 : '';
        let fullName = sgs.data.name ? sgs.data.name : sgs.data.name_short;
        $alfa2.val(alfa2);
        $fullName.val(fullName);
    },
}).suggestions();

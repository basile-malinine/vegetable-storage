/**
 * Скрипт подключается на форме legal-subject\_form.php
 * Предварительно необходимо определить countryCode ('RU' для России, 'BY' для Белоруссии, ...)
 */

const token = '3a202d94848db5bf4d7e51ce26885ad0c9440860';

const $name = $('#legalsubject-name');
const $fullName = $('#legalsubject-full_name');
const $inn = $('#legalsubject-inn');
const $director = $('#legalsubject-director');
const $address = $('#legalsubject-address');
const $isLegal = $('#legalsubject-is_legal');
let sgsPlugin = null;

function setInnSuggestions() {
    let type;
    switch (countryCode) {
        case 'RU':
            type = 'PARTY';
            $inn.attr('placeholder', 'ИНН, Название или ФИО');
            break;
        case 'BY':
            type = 'PARTY_BY';
            $inn.attr('placeholder', 'УНН, Название или ФИО');
            break;
        default:
            type = '';
            $inn.attr('placeholder', '');
    }
    if (sgsPlugin) {
        sgsPlugin.disable();
    }
    if (type === '') {
        return;
    }
    sgsPlugin = $inn.suggestions({
        token: token,
        type: type,
        minChars: 3,
        count: 10,
        deferRequestBy: 500,
        onSelect: parseParty
    }).suggestions();
}

function parseParty(sgs) {
    // Получаем объект data из выбранной подсказки
    const d = sgs.data;

    // Если требуется изменяем Тип ЮФЛ
    if (d.type === 'LEGAL' && $isLegal.val() != 1) {
        $isLegal.val(1).trigger('change');
    } else if (d.type === 'INDIVIDUAL' && $isLegal.val() != 0) {
        $isLegal.val(0).trigger('change');
    }

    let shortName = '';
    let fullName = '';
    let inn = '';
    let address = '';
    switch (countryCode) {
        case 'RU':
            // Извлекаем все необходимые поля
            shortName = d.name.short ? d.name.short : d.name.full;
            shortName += d.opf.short ? ', ' + d.opf.short : '';
            fullName = d.name.short_with_opf ? d.name.short_with_opf : '';
            inn = d.inn;
            address = d.address.unrestricted_value ? d.address.unrestricted_value : '';

            // Заполняем поля в форме
            $inn.val(inn);
            $name.val(shortName);
            $fullName.val(fullName);
            $address.val(address);

            // Если Юридическое лицо
            if (d.type === 'LEGAL') {
                let director =
                    d.management !== null
                    && d.management.post
                    && d.management.post.toLowerCase().includes('директор')
                    && d.management.name
                        ? d.management.name : '';
                $director.val(director);
            }
            break;
        case 'BY':
            // Извлекаем все необходимые поля
            if (d.type === 'LEGAL') {
                shortName = d.trade_name_ru ? d.trade_name_ru : '';
            } else if (d.type === 'INDIVIDUAL') {
                shortName = d.fio_ru ? d.fio_ru : '';
            }
            fullName = d.short_name_ru ? d.short_name_ru : '';
            inn = d.unp;
            address = d.address ? d.address : '';

            // Заполняем поля в форме
            $inn.val(inn);
            $name.val(shortName);
            $fullName.val(fullName);
            $address.val(address);
            break;
    }
}

$('document').ready(() => {
    setInnSuggestions();
});

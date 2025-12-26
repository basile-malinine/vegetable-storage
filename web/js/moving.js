$(() => {
    const $acceptance = $('#acceptance-id');
    $acceptance.on('change', changeAcceptance);
    const $stockRecipient = $('#stock-recipient-id');

    function changeAcceptance() {
        $.post(
            '/moving/change-acceptance',
            {
                'acceptance_id': $acceptance.val()
            },
            (data) => {
                const val = $stockRecipient.val() ? +$stockRecipient.val() : 0;
                $stockRecipient.children("option").remove();
                if (data) {
                    $('#hidden-company-sender-id').val(data['company_own_id']);
                    $('#company-sender-id').val(data['company_own_id']).trigger('change');
                    $('#hidden-stock-sender-id').val(data['stock_id']);
                    $('#stock-sender-id').val(data['stock_id']).trigger('change');
                    $('#hidden-company-recipient-id').val(data['company_own_id']);
                    $('#company-recipient-id').val(data['company_own_id']).trigger('change');
                    $.each(data['stock_recipient_list'], function (key, value) {
                        $stockRecipient.append($("<option>", {'value': key, 'text': value}));
                    });
                    $stockRecipient.val(0).trigger('change');
                    if (localStorage.getItem('acceptance-id') === $acceptance.val()) {
                        $stockRecipient.val(val).trigger('change');
                    } else {
                        $stockRecipient.val(0).trigger('change');
                        localStorage.setItem('acceptance-id', $acceptance.val());
                    }
                }
            }
        );
    }

    // Обработка двойного клика на запись в таблице состава Возврата
    $('.contextMenuRow').on('dblclick', function () {
        let rowId = $(this).attr('data-row-id');
        $('#modal').modal('show').find('#modalContent')
            .load(`/moving-item/edit/` + rowId);
    });

    // Инициализация контекстного меню для таблицы с Номенклатурой
    let menu = new BootstrapMenu('.contextMenuRow', {
        fetchElementData: function ($rowElem) {
            return $rowElem.data('rowId');
        },
        actions: [
            {
                name: 'Редактировать',
                iconClass: 'fa-pen',
                onClick: (id) => {
                    $('#modal').modal('show').find('#modalContent')
                        .load(`/moving-item/edit/` + id);
                }
            },
        ]
    });

    changeAcceptance();
});

$(() => {
    const $acceptance = $('#acceptance-id');
    $acceptance.on('change', changeAcceptance);
    const $type = $('#type-id');
    $type.on('change', changeType);

    function changeType() {
        $.post(
            '/increase/change-type',
            {
                'type_id': $type.val()
            },
            (data) => {
                const val = $acceptance.val() ? +$acceptance.val() : 0;
                $acceptance.children("option").remove();
                $.each(data, function (key, value) {
                    $acceptance.append($("<option>", {'value': key, 'text': value}));
                });
                $acceptance.off('change', changeAcceptance);
                $acceptance.val(val).trigger('change');
                $acceptance.on('change', changeAcceptance);
            }
        )
    }

    function changeAcceptance() {
        $.post(
            '/increase/change-acceptance',
            {
                'acceptance_id': $acceptance.val()
            },
            (data) => {
                if (data) {
                    if (actionId === 'create' && $acceptance.val()) {
                        $('#hidden-company-own-id').val(data['company_own_id']);
                        $('#company-own-id').val(data['company_own_id']).trigger('change');
                        $('#hidden-stock-id').val(data['stock_id']);
                        $('#stock-id').val(data['stock_id']).trigger('change');
                    }
                }
            }
        );
    }


    // Обработка двойного клика на запись в таблице состава Доставки
    $('.contextMenuRow').on('dblclick', function () {
        let rowId = $(this).attr('data-row-id');
        $('#modal').modal('show').find('#modalContent')
            .load(`/increase-item/edit/` + rowId);
    });

    // инициализация контекстного меню для таблицы с данными
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
                        .load(`/increase-item/edit/` + id);
                }
            },
        ]
    });

    changeAcceptance();
});

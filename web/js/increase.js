$(() => {
    const $acceptance = $('#acceptance-id');
    $acceptance.on('change', changeAcceptance);

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

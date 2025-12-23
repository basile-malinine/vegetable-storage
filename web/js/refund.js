$(() => {
    const $refundType = $('#refund-type');
    $refundType.on('change', changeRefundType);
    const $order = $('#order-id');
    $order.on('change', changeOrder);

    function changeRefundType() {
        $.post(
            '/refund/change-type',
            {
                'type_id': $refundType.val()
            },
            (data) => {
                const val = $order.val() ? +$order.val() : 0;
                $order.children("option").remove();
                $.each(data, function (key, value) {
                    $order.append($("<option>", {'value': key, 'text': value}));
                });
                if (localStorage.getItem('refund-type') === $refundType.val()) {
                    $order.val(val).trigger('change');
                } else {
                    $order.val(0).trigger('change');
                    localStorage.setItem('refund-type', $refundType.val());
                }
            }
        );
    }

    function changeOrder() {
        $.post(
            '/refund/change-order',
            {
                'type_id': $refundType.val(),
                'order_id': $order.val()
            },
            (data) => {
                $('#hidden-company-own-id').val(data['company_own_id']);
                $('#company-own-id').val(data['company_own_id']).trigger('change');
            }
        );
    }

    // Обработка двойного клика на запись в таблице состава Возврата
    $('.contextMenuRow').on('dblclick', function () {
        let rowId = $(this).attr('data-row-id');
        $('#modal').modal('show').find('#modalContent')
            .load(`/refund-item/edit/` + rowId);
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
                        .load(`/refund-item/edit/` + id);
                }
            },
        ]
    });

    changeRefundType();
});

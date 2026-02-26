$(() => {
    const $orderType = $('#order-type');
    const $orderCompany = $('#order-company-own-id');
    const $orderStock = $('#order-stock-id');
    const $divStock = $('#div-order-stock')
    const $orderExecutor = $('#order-executor-id');
    const $divExecutor = $('#div-order-executor');

    function OnChangeOrderType() {
        $orderType.on('change', changeOrderType);
        $orderCompany.on('change', changeOrderType);
        $orderStock.on('change', changeOrderType);
        $orderExecutor.on('change', changeOrderType);
    }

    function OffChangeOrderType() {
        $orderType.off('change', changeOrderType);
        $orderCompany.off('change', changeOrderType);
        $orderStock.off('change', changeOrderType);
        $orderExecutor.off('change', changeOrderType);
    }

    const $order = $('#order-id');
    $order.on('change', changeOrder);

    function changeOrderType() {
        $.post(
            '/refund/change-type',
            {
                'type_id': $orderType.val(),
                'company_id': $orderCompany.val(),
                'stock_id': $orderStock.val(),
                'executor_id': $orderExecutor.val(),
            },
            (data) => {
                // Тип Заказа == Склад
                if (+$orderType.val() === 1) {
                    $divExecutor.hide();
                    $divStock.show();
                    // Тип Заказа == Исполнитель
                } else if (+$orderType.val() === 2) {
                    $divStock.hide();
                    $divExecutor.show();
                    // Тип Заказа не указан
                } else {
                    $divStock.hide();
                    $divExecutor.hide();
                }
                const val = $order.val() ? +$order.val() : 0;
                $order.children("option").remove();
                $.each(data, function (key, value) {
                    $order.append($("<option>", {'value': key, 'text': value}));
                });
                $order.off('change', changeOrder);
                $order.val(val).trigger('change');
                $order.on('change', changeOrder);
            }
        );
    }

    function changeOrder() {
        $.post(
            '/refund/change-order',
            {
                'type_id': $orderType.val(),
                'order_id': $order.val()
            },
            (data) => {
                OffChangeOrderType();
                $('#order-company-own-id').val(data['company_own_id']).trigger('change');
                $('#order-stock-id').val(data['stock_id']).trigger('change');
                $('#order-executor-id').val(data['executor_id']).trigger('change');
                $('#hidden-company-own-id').val(data['company_own_id']);
                $('#company-own-id').val(data['company_own_id']).trigger('change');
                OnChangeOrderType();
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

    OnChangeOrderType();
    changeOrderType();
});

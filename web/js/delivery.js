$(() => {
    const $stockDiv = $("#stock-div");
    const $executorDiv = $("#executor-div");
    const $orderListDiv = $('#order-list-div');
    const $deliveryType = $('#delivery-type');
    $deliveryType.on('change', changeDeliveryType);

    function changeDeliveryType() {
        if ($deliveryType.val() === '1') {
            $stockDiv.removeAttr("hidden");
            $orderListDiv.attr("hidden", true);
            $executorDiv.attr("hidden", true);
        } else {
            $executorDiv.removeAttr("hidden");
            $stockDiv.attr("hidden", true);
            $orderListDiv.removeAttr("hidden");
        }
    }

    const $btnAddOrders = $('#btn-add-orders');
    $btnAddOrders.on('click', clickBtnAddOrders);

    // Обработка кнопки списка привязанных Заказов на форме Поставки
    function clickBtnAddOrders(e) {
        $('#modal').modal('show').find('#modalContent')
            .load(`/order/add-orders-to-delivery/${docId}`);
    }

    changeDeliveryType();
});

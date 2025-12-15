$(() => {
    const $stockDiv = $("#stock-div");
    const $executorDiv = $("#executor-div");
    const $deliveryType = $('#delivery-type');
    $deliveryType.on('change', changeDeliveryType);

    function changeDeliveryType() {
        if ($deliveryType.val() === '1') {
            $("#executorSelect").val(null).trigger("change");
            $stockDiv.removeAttr("hidden");
            $executorDiv.attr("hidden", true);
        } else {
            $("#stockSelect").val(null).trigger("change");
            $executorDiv.removeAttr("hidden");
            $stockDiv.attr("hidden", true);
        }
    }

    changeDeliveryType();
});

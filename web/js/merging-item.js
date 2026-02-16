$(() => {
    const $acceptance = $('#mergingitem-acceptance_id');
    const $itemQuantity = $('#mergingitem-quantity');
    const $itemQuantityPallet = $('#mergingitem-quantity_pallet');
    const $itemQuantityPaks = $('#mergingitem-quantity_paks');

    $acceptance.on('change', changeAcceptance);
    function changeAcceptance(e) {
        $.post(
            "/merging-item/change-acceptance",
            { acceptance_id: $acceptance.val() },
            (data) => {
                $itemQuantity.val(data["quantity"]);
                $itemQuantity.removeClass("is-invalid");

                $("#hidden-pallet-type-id").val(data["pallet_type_id"]);
                $("#pallet-type-id").val(data["pallet_type_id"]).trigger("change");

                if (+data["quantity_pallet"] > 0) {
                    $itemQuantityPallet.removeAttr("disabled");
                    $itemQuantityPallet.val(data["quantity_pallet"]);
                    $itemQuantityPallet.removeClass("is-invalid");
                } else {
                    $itemQuantityPallet.val(null);
                    $itemQuantityPallet.attr("disabled", true);
                }

                if (+data["quantity_paks"] > 0) {
                    $itemQuantityPaks.removeAttr("disabled");
                    $itemQuantityPaks.val(data["quantity_paks"]);
                    $itemQuantityPaks.removeClass("is-invalid");
                } else {
                    $itemQuantityPaks.val(null);
                    $itemQuantityPaks.attr("disabled", true);
                }
            }
        );
    }
});
$(() => {
    const $stockDiv = $("#stock-div");
    const $executorDiv = $("#executor-div");
    const $orderType = $('#order-type');
    $orderType.on('change', changeOrderType);

    function changeOrderType() {
        if ($orderType.val() === '1') {
            $("#executorSelect").val(null).trigger("change");
            $stockDiv.removeAttr("hidden");
            $executorDiv.attr("hidden", true);
        } else {
            $("#stockSelect").val(null).trigger("change");
            $executorDiv.removeAttr("hidden");
            $stockDiv.attr("hidden", true);
        }
    }

    let $selectParent = $("#buyer-select");
    let $selectChild = $("#distribution-center-select");

    function changeParent(e) {
        $.post(
            "/legal-subject/get-distribution-center",
            {id: $(this).val()},
            (data) => {
                $selectChild.children("option").remove();
                $.each(data, function (key, value) {
                    $selectChild.append($("<option>", value));
                });

                let id = +sessionStorage.getItem('childId');
                if (id) {
                    $selectChild.val(id).trigger('change');
                    sessionStorage.removeItem('childId');
                } else {
                    $selectChild.prop("selectedIndex", -1);
                }
            }
        );
    }

    function changeChild(e) {
        if (+$selectParent.val()) {
            return;
        }
        $.post(
            "/distribution-center/get-legal-subject",
            {id: $(this).val()},
            (data) => {
                sessionStorage.setItem('childId', $(this).val());
                $selectParent.val(data).trigger("change");
            }
        );
    }

    $selectParent.on('change', changeParent);
    $selectChild.on('change', changeChild);

    changeOrderType();
});

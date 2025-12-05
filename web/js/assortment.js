/*
 * Скрипт обслуживает форму Номенклатура views/assortment/_form.php
 */

$(() => {
    let $selectParent = $("#assortment-parent_id");
    let $selectChild = $("#assortment-assortment_group_id");

    function changeParent(e) {
        $.post(
            "/assortment-group/get-child",
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
            "/assortment-group/get-parent",
            {id: $(this).val()},
            (data) => {
                sessionStorage.setItem('childId', $(this).val());
                $selectParent.val(data).trigger("change");
            }
        );
    }

    $selectParent.on('change', changeParent);
    $selectChild.on('change', changeChild);
});

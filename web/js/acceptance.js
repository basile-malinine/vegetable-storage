$(() => {
    const $acceptanceType = $('#acceptance-type');
    $acceptanceType.on('change', changeAcceptanceType);
    const $parentDoc = $('#parent-doc');
    $parentDoc.on('change', changeParentDoc);
    // const $btnChangeClose = $('#btn-change-close');
    // $btnChangeClose.on('click', clickBtnChangeClose);

    function changeAcceptanceType() {
        $.post(
            '/acceptance/change-type',
            {
                'type_id': $acceptanceType.val()
            },
            (data) => {
                $parentDoc.children("option").remove();
                $.each(data, function (key, value) {
                    $parentDoc.append($("<option>", {'value': key, 'text': value}));
                });
                $parentDoc.val(0);
            }
        );
    }

    function changeParentDoc() {
        $.post(
            '/acceptance/change-parent-doc',
            {
                'type_id': $acceptanceType.val(),
                'parent_doc_id': $parentDoc.val()
            },
            (data) => {
                $('#delivery-id').val(data['delivery_id']);
                $('#company-own-id').val(data['company_own_id']);
                $('#stock-id').val(data['stock_id']);
            }
        );
    }

    function clickBtnChangeClose() {
        $.post(
            '/acceptance/change-close',
            {
                'id': docId
            },
            (data) => {
                // alert(data);
                return false;
            }
        );
    }

    // Обработка двойного клика на запись в таблице состава Доставки
    $('.contextMenuRow').on('dblclick', function () {
        let rowId = $(this).attr('data-row-id');
        $('#modal').modal('show').find('#modalContent')
            .load(`/acceptance-item/edit/` + rowId);
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
                        .load(`/acceptance-item/edit/` + id);
                }
            },
        ]
    });

    changeAcceptanceType();
});

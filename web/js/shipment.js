$(() => {
    const $shipmentType = $('#shipment-type');
    $shipmentType.on('change', changeShipmentType);
    const $parentDoc = $('#parent-doc');
    $parentDoc.on('change', changeParentDoc);
    const $btnAdd = $('#btn-add');
    $btnAdd.on('click', clickBtnAdd);

    function changeShipmentType() {
        $.post(
            '/shipment/change-type',
            {
                'type_id': $shipmentType.val()
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
            '/shipment/change-parent-doc',
            {
                'type_id': $shipmentType.val(),
                'parent_doc_id': $parentDoc.val()
            },
            (data) => {
                $('#delivery-id').val(data['delivery_id']);
                $('#company-own-id').val(data['company_own_id']);
                $('#stock-id').val(data['stock_id']);
            }
        );
    }

    // Обработка кнопки 'Добавить' на форме Отгрузки
    function clickBtnAdd(e) {
        $('#modal').modal('show').find('#modalContent')
            .load('/shipment-acceptance/add/' + docId);
    }

    // Обработка двойного клика на запись в таблице Приёмок
    $('.contextMenuRow').on('dblclick', function () {
        let rowId = $(this).attr('data-row-id');
        $('#modal').modal('show').find('#modalContent')
            .load('/shipment-acceptance/edit/' + rowId);
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
                        .load(`/shipment-acceptance/edit/` + id);
                }
            },

            {
                name: 'Удалить',
                iconClass: 'fa-trash-alt',
                onClick: (id) => {
                    if (confirm("Вы точно хотите удалить запись?")) {
                        const arrIds = id.split('/');
                        let id2 = null;
                        if (arrIds.length === 2) {
                            id = arrIds[0];
                            id2 = arrIds[1];
                        }
                        $.post(
                            '/shipment-acceptance/remove',
                            {
                                id: id,
                                id2: id2
                            },
                            (data) => {
                                $.pjax.reload({
                                    container: '#shipment-acceptance-grid',
                                    async: false
                                });
                                $.pjax.reload('#shipment-buttons');
                            }
                        );
                    }
                }
            }
        ]
    });

    changeShipmentType();
});

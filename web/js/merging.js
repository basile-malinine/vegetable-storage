$(() => {
    const $assortment = $('#assortment');
    const $assortmentInfo = $('#assortment-info');
    const $quantity = $('#quantity');
    const $btnAdd = $('#btn-add');

    $assortment.on('change', changeAssortment);
    $quantity.on('change', changeAssortment);

    function changeAssortment(e) {
        $.post(
            '/merging/change-assortment',
            {
                'assortment_id': $assortment.val(),
            },
            (data) => {
                $assortmentInfo.val(data);
            }
        );
    }

    $btnAdd.on('click', clickBtnAdd);

    function clickBtnAdd(e) {
        $('#modal').modal('show').find('#modalContent')
            .load('/merging-item/add/' + docId);
    }

    // Обработка двойного клика на запись в таблице Приёмок
    $('.contextMenuRow').on('dblclick', function () {
        let rowId = $(this).attr('data-row-id');
        $('#modal').modal('show').find('#modalContent')
            .load('/merging-item/edit/' + rowId);
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
                        .load(`/merging-item/edit/` + id);
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
                            '/merging-item/remove',
                            {
                                id: id,
                                id2: id2
                            },
                            (data) => {
                                $.pjax.reload({
                                    container: '#merging-item-grid',
                                    async: false
                                });
                                $.pjax.reload('#merging-qnt-weight-info');
                            }
                        );
                    }
                }
            }
        ]
    });

    changeAssortment();
});
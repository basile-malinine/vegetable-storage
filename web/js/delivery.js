$(() => {
    const $btnAdd = $('#btnAdd');
    $btnAdd.on('click', clickBtnAdd);

    // Обработка кнопки Добавить на форме Доставки
    function clickBtnAdd(e) {
        $('#modal').modal('show').find('#modalContent')
            .load('/delivery-item/add/' + deliveryId);
    }

    // Обработка двойного клика на запись в таблице состава Доставки
    $('.contextMenuRow').on('dblclick', function () {
        let rowId = $(this).attr('data-row-id');
        $('#modal').modal('show').find('#modalContent')
            .load('/delivery-item/edit/' + rowId);
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
                        .load('/delivery-item/edit/' + id);
                }
            },

            {
                name: 'Удалить',
                iconClass: 'fa-trash-alt',
                onClick: (id) => {
                    if (confirm("Вы точно хотите удалить запись?")) {
                        $.post(
                            "/delivery-item/remove",
                            {
                                id: id,
                            },
                            (data) => {
                                if (data === 'false') {
                                    alert('Запись используется в других таблицах!');
                                }
                                $.pjax.reload({container: "#delivery-items"});
                            }
                        );
                    }
                }
            }
        ]
    });
});

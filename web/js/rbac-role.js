$(() => {
    const $listRoles = $('#roles');
    const $addRole = $('#add-role');
    const $editRole = $('#edit-role');
    const $removeRole = $('#remove-role');
    const $labelRoleName = $('#role-name');
    const $listRolePermissions = $('#role-permissions')
    const $listPermissions = $('#permissions');
    const $addPermissions = $('#add-permissions');
    const $removePermissions = $('#remove-permissions');

    // Добавление роли
    function clickAddRole(e) {
        $('#modal').modal('show').find('#modalContent').load('/rbac/add-role');
        sessionStorage.setItem('rbac.currRole', $listRoles.val());
    }
    $addRole.on('click', clickAddRole);

    // Изменение названия роли
    function clickEditRole(e) {
        $('#modal').modal('show').find('#modalContent').load('/rbac/edit-role/' + $listRoles.val());
        sessionStorage.setItem('rbac.currRole', $listRoles.val());
    }
    $editRole.on('click', clickEditRole);

    // Удаление роли
    function clickRemoveRole(e) {
        $('#modal').modal('show').find('#modalContent').load('/rbac/remove-role/' + $listRoles.val());
    }
    $removeRole.on('click', clickRemoveRole);

    // Изменение выбора в списке ролей
    function changeListRoles(e) {
        $.post(
            '/rbac/get-permissions-by-role',
            {
                name: $listRoles.val(),
            },
            (data) => {
                if (data) {
                    $listRolePermissions.empty();
                    $.each(data[0], (key, val) => {
                        $listRolePermissions.append('<option value=' + key + '>' + val + '</option>');
                    });
                    $listPermissions.empty();
                    $.each(data[1], (key, val) => {
                        $listPermissions.append('<option value=' + key + '>' + val + '</option>');
                    });
                }
            }
        );
        // Обновляем метку
        $labelRoleName.text($('#roles option:selected').text());
        // Записываем в сессию браузера выбранную роль.
        sessionStorage.setItem('rbac.currRole', $listRoles.val())
    }
    $listRoles.on('change', changeListRoles)

    // Добавление разрешений
    function clickAddPermissions(e) {
        const role = $listRoles.val();
        const perms = $listPermissions.val();
        $.post(
            '/rbac/add-role-permissions',
            {
                role: role,
                permissions: perms,
            },
            () => {
                location.reload();
            }
        );
        $('#permissions option').prop('selected', false);
        $('#role-permissions option').prop('selected', false);
    }
    $addPermissions.on('click', clickAddPermissions);

    // Удаление разрешений
    function clickRemovePermissions(e) {
        const role = $listRoles.val();
        const perms = $listRolePermissions.val();
        $.post(
            '/rbac/remove-role-permissions',
            {
                role: role,
                permissions: perms,
            },
            () => {
                location.reload();
            }
        );
        $('#permissions option').prop('selected', false);
        $('#role-permissions option').prop('selected', false);
    }
    $removePermissions.on('click', clickRemovePermissions);

    // Выбор в списке назначенных разрешений
    function selectListRolePermissions(e) {
        $removePermissions.removeClass('disabled')
    }
    $listRolePermissions.on('change', selectListRolePermissions);

    // Выбор в списке доступных разрешений
    function selectListPermissions(e) {
        $addPermissions.removeClass('disabled')
    }
    $listPermissions.on('change', selectListPermissions);



    if ($('#roles option').length) {
        const val = sessionStorage.getItem('rbac.currRole');
        if (val && val !== 'undefined') {
            $(`#roles option[value=${val}]`).prop('selected', true);
        } else {
            $('#roles option:first').prop('selected', true);
        }
        changeListRoles(null);
    } else {
        $removeRole.addClass('disabled');
    }

    $addPermissions.addClass('disabled');
    $removePermissions.addClass('disabled');

    $listRoles.focus();
});
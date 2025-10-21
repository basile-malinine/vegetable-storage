$(() => {
    const $listUserRoles = $('#userRoles');
    const $listRoles = $('#roles');
    const $addRole = $('#addRole');
    const $removeRole = $('#removeRole');

    function changeListUserRoles(e) {
        $removeRole.removeClass('disabled');
    }
    $listUserRoles.on('change', changeListUserRoles);

    function changeListRoles(e) {
        $addRole.removeClass('disabled');
    }
    $listRoles.on('change', changeListRoles);

    function clickAddRole(e) {
        const role = $listRoles.val();
        $.post(
            '/rbac/add-role-to-user',
            {
                role: role,
                userId: userId,
            },
            () => {
                location.reload();
            }
        );
    }
    $addRole.on('click', clickAddRole);

    function clickRemoveRole(e) {
        const role = $listUserRoles.val();
        $.post(
            '/rbac/remove-role-from-user',
            {
                role: role,
                userId: userId,
            },
            () => {
                location.reload();
            }
        );
    }
    $removeRole.on('click', clickRemoveRole);

    $addRole.addClass('disabled');
    $removeRole.addClass('disabled');
});
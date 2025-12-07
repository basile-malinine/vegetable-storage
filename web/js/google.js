$(() => {
    const googleUpdate = $('#google-update');
    googleUpdate.on('click', clickGoogleUpdate);

    function clickGoogleUpdate(e) {
        $.post(
            "/" + controllerName + "/google-update/",
            null,
            (data) => {
                console.log(data);
            }
        );
    }
});

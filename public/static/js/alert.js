function imgDialog (url) {
    layer.open({
        type: 1,
        content: '<img src="' + url + '" class="dialog-img" />',
        maxWidth: window.screen.width / 2,
        maxHeight: window.screen.height / 2
    })
}
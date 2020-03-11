$(document).ready(function () {
    $('.formsendmessage').submit(function (e) {
        if(!$('.emailsend').val().match(/^\w*([.|-]){0,1}\w*([.|-]){0,1}\w*[@][a-z]*[.]\w{2,5}/)){
            e.preventDefault();
        }
    })
})

$(function() {
    $('#btnAvatar').on('click', function() {
        $('#avatar').trigger('click');
    });


    $('#avatar').on('change', function() {
        $('#formAvatar').trigger('submit');
    });


    
});
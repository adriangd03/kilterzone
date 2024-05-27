import toastAlerts from './modules/alerts.js';
const rutaId = document.getElementById('rutaId').value;
$(function(){

    $('#like').on('click', function(){
        axios.get('/ruta/like/'+rutaId)
        .then(function(response){
            if($('#like').hasClass('bi-heart')){
                $('#like').removeClass('bi-heart');
                $('#like').addClass('bi-heart-fill');
            }else{
                $('#like').removeClass('bi-heart-fill');
                $('#like').addClass('bi-heart');
            }
        })
        .catch(function(error){
            console.log(error);
            toastAlerts.mostrarErrors(error.response.data.errors);
        });
    });


});
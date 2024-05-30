import comentaris from './modules/comentarisGuest.js';

$(function () {
    const rutaId = document.getElementById('rutaId').value;
    const channel = window.Echo.channel('ruta.' + rutaId);
    channel
        .listen('.NouComentari', function (data) {
            if (data.comentari_id) {
                comentaris.mostrarRespostaRebuda(data.user, data.nouComentari, data.comentari_id);
            } else {
                comentaris.mostrarComentariRebut(data.user, data.nouComentari);
            }
        })
        .listen('.EditarComentari', function (data) {
            console.log(data);
            comentaris.editarComentari(data.comentari.id, data.comentari.comentari);
        })
        .listen('.EliminarComentari', function (data) {
            console.log(data);
            comentaris.eliminarComentari(data.comentari_id);
        })
        ;
});
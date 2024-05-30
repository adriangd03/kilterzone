import toastAlerts from './modules/toastAlerts.js';
import comentaris from './modules/comentarisCreador.js';
import { $comentariId, $inputComentari } from './modules/constantRuta.js';


$(function () {
    const rutaId = document.getElementById('rutaId').value;
    const $like = $('#like');
    const $likeHeart = $('#like i');
    const $totalLikes = $('#totalLikes');
    const $escalat = $('#escalat');
    const $totalEscalat = $('#totalEscalat');
    const userId = $('#userId').val();
    const channel = window.Echo.channel('ruta.' + rutaId);

    // Set all input text to set value
    $( 'input[type="text"]' ).each(function() {
        $(this).val($(this).attr('value'));
    });


    $inputComentari.on('keyup', comentaris.inputComentariKeyUp);

    $($like).on('click', function () {
        axios.get('/ruta/like/' + rutaId)
            .then(function (response) {
                if (response.data.liked) {
                    $($likeHeart).removeClass('bi-heart');
                    $($likeHeart).addClass('bi-heart-fill text-danger');
                    $totalLikes.html(parseInt($totalLikes.html()) + 1);
                } else {
                    $($likeHeart).removeClass('bi-heart-fill text-danger');
                    $($likeHeart).addClass('bi-heart');
                    $totalLikes.html(parseInt($totalLikes.html()) - 1);
                }
            })
            .catch(function (error) {
                console.log(error);
                toastAlerts.mostrarErrors(error.response.data.errors);
            });
    });

    $($escalat).on('click', function () {
        axios.get('/ruta/escalat/' + rutaId)
            .then(function (response) {
                if (response.data.escalada) {
                    $totalEscalat.html(parseInt($totalEscalat.html()) + 1);
                    $escalat.removeClass('opacity-50');
                } else {
                    $totalEscalat.html(parseInt($totalEscalat.html()) - 1);
                    $escalat.addClass('opacity-50');
                }
            })
            .catch(function (error) {
                console.log(error);
                toastAlerts.mostrarErrors(error.response.data.errors);
            });
    });

    $('[name="respondre"]').on('click', comentaris.listenerRespondre);

    $('#formComentari').on('submit', function (e) {
        e.preventDefault();

        let data = new FormData(e.target);

        axios.post('/ruta/comentari', data)
            .then(function (response) {
                console.log(response);
                toastAlerts.mostrarToast('success', 'Comentari creat correctament');
                if (response.data.comentariId) {
                    console.log(response.data.comentariId);
                    comentaris.mostrarRespostaEnviada(response.data.user, response.data.comentari, response.data.comentariId);
                } else {
                    comentaris.mostrarComentariEnviat(response.data.user, response.data.comentari);
                }

                e.target.reset();
                $comentariId.val('');
            })
            .catch(function (error) {
                console.log(error);
                toastAlerts.mostrarErrors(error.response.data.error);
            });
    });

    $('[name="formEliminarComentari"]').on('submit', function (e) {
        e.preventDefault();

        if(!confirm('Estàs segur que vols eliminar el comentari?')) {
            return;
        }



        let data = new FormData(e.target);

        axios.post('/ruta/comentari/eliminar', data)
            .then(function (response) {
                console.log(response);
                toastAlerts.mostrarToast('success', 'Comentari eliminat correctament');
                comentaris.eliminarComentari(response.data.comentariId);
            })
            .catch(function (error) {
                console.log(error);
                toastAlerts.mostrarErrors(error.response.data.error);
            });
    });

    $('[name="formEditarComentari"]').on('submit', function (e) {
        e.preventDefault();

        let data = new FormData(e.target);

        axios.post('/ruta/comentari/editar', data)
            .then(function (response) {
                console.log(response);
                toastAlerts.mostrarToast('success', 'Comentari editat correctament');
                comentaris.editarComentari(response.data.comentariId, response.data.comentari);
            })
            .catch(function (error) {
                console.log(error);
                toastAlerts.mostrarErrors(error.response.data.error);
            });
    });




    channel
        .listen('.NouComentari', function (data) {
            if (data.user.id == userId) return;

            console.log(data);

            if (data.comentari_id) {
                comentaris.mostrarRespostaRebuda(data.user, data.nouComentari, data.comentari_id);
            } else {
                comentaris.mostrarComentariRebut(data.user, data.nouComentari);
            }
        })
        .listen('.EditarComentari', function (data) {
            if (data.comentari.user_id == userId) return;
            comentaris.editarComentari(data.comentari.id, data.comentari.comentari);
        })
        .listen('.EliminarComentari', function (data) {
            if (data.comentari_id == userId && !data.isCreador) return;
            console.log(data);
            comentaris.eliminarComentari(data.comentari_id);
        })
        ;


});
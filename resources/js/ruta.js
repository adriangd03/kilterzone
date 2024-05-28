import toastAlerts from './modules/toastAlerts.js';
import comentaris from './modules/comentaris.js';

$(function () {
    const rutaId = document.getElementById('rutaId').value;
    const $like = $('#like');
    const $likeHeart = $('#like i');
    const $totalLikes = $('#totalLikes');
    const $escalat = $('#escalat');
    const $totalEscalat = $('#totalEscalat');
    const userId = $('#userId').val();
    const channel = window.Echo.channel('ruta.' + rutaId);

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
                } else {
                    $totalEscalat.html(parseInt($totalEscalat.html()) - 1);
                }
            })
            .catch(function (error) {
                console.log(error);
                toastAlerts.mostrarErrors(error.response.data.errors);
            });
    });

    $('#formComentari').on('submit', function (e) {
        e.preventDefault();

        let data = new FormData(e.target);

        axios.post('/ruta/comentari', data)
            .then(function (response) {
                console.log(response);
                toastAlerts.mostrarToast('success', 'Comentari creat correctament');
                comentaris.mostrarComentariEnviat(response.data.user ,data.get('comentari'));
                e.target.reset();
            })
            .catch(function (error) {
                console.log(error);
                toastAlerts.mostrarErrors(error.response.data.error);
            });
    });

    
    channel
    .listen('.like', function (data) {
        if (data.liked) {
            $($likeHeart).removeClass('bi-heart');
            $($likeHeart).addClass('bi-heart-fill text-danger');
            $totalLikes.html(parseInt($totalLikes.html()) + 1);
        } else {
            $($likeHeart).removeClass('bi-heart-fill text-danger');
            $($likeHeart).addClass('bi-heart');
            $totalLikes.html(parseInt($totalLikes.html()) - 1);
        }})
        .listen('.NouComentari', function (data) {
            if(data.user.id == userId) return;
            comentaris.mostrarComentariRebut(data.comentari, data.user);
        })
        ;


});
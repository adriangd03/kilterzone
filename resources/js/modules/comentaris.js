const $comentaris = $('#comentaris');
import { $comentariId, $inputComentari, } from './constantRuta.js';
import toastAlerts from './toastAlerts.js';

let usernameRespondre = '';

/**
 * Funció que crea un comentari rebut	
 * @param {Object} user 
 * @param {Object} comentari 
 * @returns {JQuery} Div amb el comentari rebut
 */
function crearComentariRebut(user, comentari) {
    let $comentariDiv = $('<div>', { class: 'd-flex mb-4' });
    $comentariDiv.append(
        $('<div>', { class: "d-flex w-100" }).append(
            $('<a>', { class: "text-reset text-decoration-none", href: '/perfil/' + user.id }).append(
                $('<img>', { class: "rounded-circle border me-1", src: user.avatar, alt: 'avatar', width: "40px", height: "40px" }))
            , $('<div>', { class: 'd-flex flex-column w-100' }).append(
                $('<div>', { class: 'd-flex align-items-baseline' }).append(
                    $('<a>', { class: "text-reset text-decoration-none", href: '/perfil/' + user.id }).append(
                        $('<span>', { class: "fw-bold" }).html(user.username))
                    , $('<p>', { id: `comentari-${comentari.id}`, class: "m-0 ms-2 small" }).html(comentari.comentari))
                ,
                $('<div>', { class: 'd-flex', id: `info-${comentari.id}` }).append(
                    $('<span>', { class: "small text-muted me-2" }).html('Nou')
                    , $('<button>', { class: "btn p-0 border-0 text-muted", type: "button", id: `${comentari.id}`, 'data-username': `${user.username}` }).append(
                        $('<span>', { class: "small align-top" }).html('Respondre')).on('click', comentaris.listenerRespondre)
                ),
                $('<div>', { id: `divRespostes-${comentari.id}`, class: 'd-flex mt-2 d-none' }).append(
                    $('<button>', { class: 'btn p-0 border-0 text-muted', id: `veure-respostes-${comentari.id}`, name: 'respostes', type: 'button', 'data-bs-toggle': 'collapse', 'data-bs-target': `#respostes-${comentari.id}`, 'aria-expanded': 'false', 'aria-controls': 'collapseExample' }).append(
                        $('<span>', { class: 'small align-top' }).html('Veure respostes').append(
                            $('<i>', { class: 'bi bi-caret-down-fill' })
                        )
                    )
                ),
                $('<div>', { id: `respostes-${comentari.id}`, class: 'collapse' })

            )
        ));
    return $comentariDiv;
}

/**
 * Funció que crea un comentari enviat
 * @param {Object} user
 * @param {Object} comentari
 * @returns {JQuery} Element HTML amb el comentari creat
*/
function crearComentariEnviat(user, comentari) {
    let $comentariDiv = $('<div>', { class: 'd-flex mb-4' });
    $comentariDiv.append(
        $('<div>', { class: "d-flex w-100" }).append(
            $('<a>', { class: "text-reset text-decoration-none", href: '/perfil/' + user.id }).append(
                $('<img>', { class: "rounded-circle border me-1", src: user.avatar, alt: 'avatar', width: "40px", height: "40px" }))
            , $('<div>', { class: 'd-flex flex-column w-100' }).append(
                $('<div>', { class: 'd-flex align-items-baseline' }).append(
                    $('<a>', { class: "text-reset text-decoration-none", href: '/perfil/' + user.id }).append(
                        $('<span>', { class: "fw-bold" }).html(user.username))
                    , $('<p>', { id: `comentari-${comentari.id}` ,class: "m-0 ms-2 small" }).html(comentari.comentari))
                ,
                $('<div>', { class: 'd-flex', id: `info-${comentari.id}` }).append(
                    $('<span>', { class: "small text-muted me-2" }).html('Nou'),
                    $('<button>', { class: "btn p-0 border-0 text-muted", type: "button", id: `${comentari.id}`, 'data-username': `${user.username}` }).append(
                        $('<span>', { class: "small align-top" }).html('Respondre')).on('click', comentaris.listenerRespondre),
                    $('<button>', { class: 'btn p-0 border-0 text-muted ms-2', type: 'button', 'data-bs-toggle': 'modal', 'data-bs-target': '#editarComentariModal', 'data-comentari-id': `${comentari.id}`, }).append(
                        $('<i>', { class: 'bi bi-three-dots' })
                    ).on('click', comentaris.mostrarModalOpcionsComentari)
                ),
                $('<div>', { id: `divRespostes-${comentari.id}`, class: 'd-flex mt-2 d-none' }).append(
                    $('<button>', { class: 'btn p-0 border-0 text-muted', id: `veure-respostes-${comentari.id}`, name: 'respostes', type: 'button', 'data-bs-toggle': 'collapse', 'data-bs-target': `#respostes-${comentari.id}`, 'aria-expanded': 'false', 'aria-controls': 'collapseExample' }).append(
                        $('<span>', { class: 'small align-top' }).html('Veure respostes').append(
                            $('<i>', { class: 'bi bi-caret-down-fill' })
                        )
                    )
                ),
                $('<div>', { id: `respostes-${comentari.id}`, class: 'collapse' })

            )
        ));
    return $comentariDiv;
}



let comentaris = {
    /**
     * Funció que mostra un comentari rebut
     * @param {string} comentari Comentari rebut
     * @param {object} user Usuari amic que ha enviat el comentari
     */
    mostrarComentariRebut: function (user, comentari) {
        let comentariDiv = crearComentariRebut(user, comentari,);
        $comentaris.append(comentariDiv);

    }
    ,
    /**
     * Funció que mostra un comentari enviat
     * @param {string} comentari Comentari enviat per l'usuari
     */
    mostrarComentariEnviat: function (user, comentari) {
        let comentariDiv = crearComentariEnviat(user, comentari);
        $comentaris.append(comentariDiv);
    },
    /**
     * Funció per escoltar el click al botó de respondre
     * @param {Event} e Event que es produeix al clicar el botó
     */
    listenerRespondre: function (e) {
        console.log(e.target);
        // Obtenim el username del comentari al que volem respondre i la id del comentari 
        let $button = $(e.target).closest('button');
        let username = $button.attr('data-username');
        let comentariId = $button.attr('id');
        // Afegim el username al input de comentari i la id del comentari
        $inputComentari.val('@' + username + ' ');
        usernameRespondre = username;
        $comentariId.val(comentariId);

        // Afegim el focus al input i movem el cursor al final del text
        $inputComentari.trigger('focus', 1000);
        let tempValue = $inputComentari.val();
        $inputComentari.val('');
        $inputComentari.val(tempValue);

        // Mostrem al usuari que està responent a al comentari
        toastAlerts.mostrarToast('info', 'Responent a ' + username);
    }
    ,
    inputComentariKeyUp: function (e) {
        if (($inputComentari.val()).indexOf('@' + usernameRespondre) == -1 && usernameRespondre != '') {
            $comentariId.val('');
            usernameRespondre = '';
            toastAlerts.mostrarToast('info', 'Has deixat de respondre al comentari');
        }

    },

    /**
     * Funció per mostrar una resposta a un comentari
     * @param {object} comentari Comentari rebut
     * @param {object} user Usuari amic que ha enviat el comentari
     * @param {string} comentariId Id del comentari al que s'està responent
     */
    mostrarRespostaEnviada: function (user, comentari, comentariId) {
        console.log(comentariId);
        if ($(`#divRespostes-${comentariId}`).hasClass('d-none')) {
            $(`#divRespostes-${comentariId}`).removeClass('d-none');
        }
        $(`#respostes-${comentariId}`).append(crearComentariEnviat(user, comentari));

    },
    /**
     * 
     * @param {object} user 
     * @param {object} comentari 
     * @param {int} comentariId 
     */
    mostrarRespostaRebuda: function (user, comentari, comentariId) {
        if ($(`#divRespostes-${comentariId}`).hasClass('d-none')) {
            $(`#divRespostes-${comentariId}`).removeClass('d-none');
        }
        $(`#respostes-${comentariId}`).append(crearComentariRebut(user, comentari));

    },
    /**
     * Funció per eliminar un comentari
     * @param {int} comentariId 
     */
    eliminarComentari: function (comentariId) {
        $(`#comentari-${comentariId}`).html('[Comentari eliminat]');
    },

    /**
     * Funció per editar un comentari
     * @param {int} comentariId Id del comentari a editar
     * @param {string} comentari Comentari editat
     */
    editarComentari: function (comentariId, comentari) {
        $(`#comentari-${comentariId}`).html(comentari);
        if($(`#editat-${comentariId}`).length){
            $(`#editat-${comentariId}`).html('Editat just ara');
            console.log('hola');
        }else{
            $($('<span>', { class: 'small text-muted me-2', id: `editat-${comentariId}` }).html('Editat just ara')).insertAfter($(`#info-${comentariId}`).children()[0]);
        }
    },

    /**
     * Funció per mostrar el modal de opcions de comentari i ficar les dades
     * @param {Event} e Event que es produeix al clicar el botó
     * 
     */
    mostrarModalOpcionsComentari: function (e) {
        let $button = $(e.target).closest('button');
        let comentariId = $button.attr('data-comentari-id');
        let comentariText = $(`#comentari-${comentariId}`).html();
        $('#editarComentariId').val(comentariId);
        $('#editarComentariInput').val(comentariText);
        $('#eliminarComentariId').val(comentariId);


    }




};
export default comentaris;


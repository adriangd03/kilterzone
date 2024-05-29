const $comentaris = $('#comentaris');
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

    },
    mostrarRespostaRebuda: function (user, comentari, comentariId) {
        if ($(`#divRespostes-${comentariId}`).hasClass('d-none')) {
            $(`#divRespostes-${comentariId}`).removeClass('d-none');
        }
        $(`#respostes-${comentariId}`).append(crearComentariRebut(user, comentari));
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
            $(`#info-${comentariId}`).append($($('<span>', { class: 'small text-muted me-2', id: `editat-${comentariId}` }).html('Editat just ara')));
        }
    },
    /**
     * Funció per eliminar un comentari
     * @param {int} comentariId 
     */
    eliminarComentari: function (comentariId) {
        $(`#comentari-${comentariId}`).html('[Comentari eliminat]');
    }
};
export default comentaris;



const $comentaris = $('#comentaris');

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
                    , $('<p>', { class: "m-0 ms-2 small" }).html(comentari))
                ,
                $('<div>', { class: 'd-flex' }).append(
                    $('<span>', { class: "small text-muted me-2" }).html('Nou')
                    ,$('<button>', { class: "btn p-0 border-0 text-muted", type: "button" }).append(
                        $('<span>', { class: "small align-top" }).html('Respondre'))))
            , $('<button>', { class: "btn p-0 border-0 me-2 ", type: "button" }).append(
                $('<i>', { class: "bi bi-heart" }))
        ));
    return $comentariDiv;
}

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
                    , $('<p>', { class: "m-0 ms-2 small" }).html(comentari))
                ,
                $('<div>', { class: 'd-flex' }).append(
                    $('<span>', { class: "small text-muted me-2" }).html('Nou')
                    ,$('<button>', { class: "btn p-0 border-0 text-muted", type: "button" }).append(
                        $('<span>', { class: "small align-top" }).html('Respondre'))))
            , $('<button>', { class: "btn p-0 border-0 me-2 ", type: "button" }).append(
                $('<i>', { class: "bi bi-heart" }))
        ));
    return $comentariDiv;
}


let comentaris = {
    /**
     * Funció que mostra un comentari rebut
     * @param {string} comentari Comentari rebut
     * @param {object} user Usuari amic que ha enviat el comentari
     */
    mostrarComentariRebut: function (comentari, user) {
        let comentariDiv = crearComentariRebut(user, comentari,);
        $comentaris.append(comentariDiv);

    }
    ,
    /**
     * Funció que mostra un comentari enviat
     * @param {string} comentari Comentari enviat per l'usuari
     */
    mostrarComentariEnviat: function (user,comentari) {
        let comentariDiv = crearComentariEnviat(user,comentari);
        $comentaris.append(comentariDiv);
    },
};

export default comentaris;
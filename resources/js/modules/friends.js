import { chat, authUserAvatar, receiver, divSolAmics, $solAmicsBadge, $solAmicsBadgeValue, userId, form, notificacionsBadge } from './constantChatFriends.js';
import missatges from './chat.js';
import toastAlerts from './toastAlerts.js';
import notificacions from './notificacions.js';
/**
 * Funció per seleccionar un usuari i obrir el xat amb ell
 * @param {object} e Event del click
 * @returns returns quan es fa clic per tancar el chat
 */
export function seleccionarUsuari(e) {
    // Evitar que quan el usuari faci doble click es seleccioni el text
    if (e.detail === 2) e.preventDefault();


    // Comprovem que no hi hagin clicat en el link de visitar perfil
    if (e.target.tagName === "A") {
        return;
    }

    // Agafar el user div que s'ha clicat
    const userDiv = $(e.target).closest(".friend")[0];
    const friendId = userDiv.id;
    const friendAvatar = $(userDiv).find("img").attr("src");
    const friendUsername = $(userDiv).find(".friend-username").text();

    // Remoure la classe user-selected de tots els usuaris
    $('.user-selected').removeClass("user-selected");

    // Comparar si el user clicat es el mateix que el user seleccionat
    if (receiver.value == friendId) {
        receiver.value = 0;
        $(chat).html("");
        $('#chat-user-name').text("");
        $('#typing').hide();
        return;
    }

    // Afegir la classe user-selected al user clicat
    $(userDiv).addClass("user-selected");

    // Setejar el valor del receiver input amb l'id del user clicat
    $(receiver).val(friendId);

    // Mostrar el nom de l'usuari al xat
    $('#chat-user-name').text(friendUsername);
    $('#typing').hide();

    // Esborrar tots els missatges del xat
    $(chat).html("");

    // Comprobem si ja hem carregat els missatges d'aquest usuari
    if (missatges.messages[friendId]) {
        // Mostrar els missatges
        missatges.mostrarMissatges(missatges.messages[friendId].messages, { id: friendId, avatar: friendAvatar, username: friendUsername });
        // Marcar els missatges com llegits
        missatges.marcarMissatgesComLlegits(friendId);
        // Netejar les notificacions de l'usuari
        notificacions.cleanUserNotifications(friendId);
    } else {
        // Mostrar els missatges
        missatges.grabUserMessages(friendId, friendAvatar, friendUsername);
    }
}
/**
 * Funció per crear un formulari per afegir un amic
 * @param {int} friendId id de l'usuari a afegir
 * @returns {jquery} retorna el formulari creat
 */
function crearFormAfegirAmic(friendId) {
    let form = $('<form>', { id: `formAfegirAmic${friendId}`, name: `formAfegirAmic${friendId}`, action: "afegirAmic", method: "POST" });
    form.append($('<input>', { type: "hidden", name: "friend_id", value: friendId }));
    form.append($('<button>', { class: "btn btn-primary", type: "submit" }).text("Afegir amic"));
    form.on('submit', friends.enviarSolAmic);
    return form;
}
/**
 * Funció per crear un formulari per eliminar un amic
 * @param {int} friendId id de l'amic a eliminar
 * @returns {jquery} retorna el formulari creat
 */
function crearFormEliminarAmic(friendId) {
    let form = $('<form>', { id: "formEliminarAmic", name: "formEliminarAmic", action: "/eliminarAmic", method: "POST" });
    form.append($('<input>', { type: "hidden", name: "friend_id", value: friendId }));
    form.append($('<button>', { class: "btn btn-danger", type: "submit" }).text("Eliminar amic"));
    form.on('submit', friends.eliminarAmic);

    return form;
}

/**
 * Funció per eliminar un amic de la llista d'amics i afegir-lo a la llista de usuaris
 * @param {int} friendId id de l'amic a eliminar
 */
function eliminarAmicHtml(friendId) {
    notificacions.cleanUserNotifications(friendId);
    $(`#${friendId}`).remove();
    $(`#totalAmics${friendId}`).html(parseInt($(`#totalAmics${friendId}`).html()) - 1);
    $(`#formEliminarAmic`).remove();
    let form = friends.crearFormAfegirAmic(friendId);
    $(`#divFormFriend${friendId}`).append(form);
    receiver.value = 0;
    $(chat).html("");
}

var friends = {

    crearFormAfegirAmic: crearFormAfegirAmic
    ,
    crearFormEliminarAmic: crearFormEliminarAmic
    ,
    eliminarAmicHtml: eliminarAmicHtml
    ,
    /**
     * Funció per mostrar a un nou amic
     * @param {object} friend 
     * @param {jquery} $element 
     */
    mostrarNouAmic: function (friend, $element) {
        var $div = $('<div>', { class: "friend col-3 p-2 justify-content-center position-relative text-center user-hover", id: friend.id });
        $div.append($('<div>', { class: "user-info" }).append
            ($('<img>', { class: "rounded-circle border", src: friend.avatar, alt: "avatar 1", style: "width: 45px; height: 45px;" }),
                $('<div>', { class: "card-text" }).append
                    ($('<div>', { class: "friend-username" }).text(friend.username),
                        $('<div>', { class: "user-status", id: `status${friend.id}` }).text("Online")),
                $('<a>', { href: `/perfil/${friend.id}`, class: "btn btn-primary" }).text("Visitar perfil")));

        var $badge = $('<span>', { class: "badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle", id: `b-${friend.id}` });
        $badge.hide();
        $badge.html(0);
        $div.append($badge);
        $div.on('click', seleccionarUsuari);
        $element.append($div);
    },
    /**
     * Funció per mostrar un nou usuari no amic
     * @param {object} friend informació de l'usuari que era amic
     * @param {jquery} $element element on es mostrarà l'usuari
     */
    mostrarNouUsuariNoAmic: function (friend) {
        let $element = $('#users');
        var $a = $('<a>', { href: `/perfil/${friend.id}`, class: " text-decoration-none rounded user-hover", id: `divNotFriend-${friend.id}` });
        $a.append($('<div>', { class: "row m-1 w-100 pt-2 pb-2" })
            .append($('<div>', { class: "col align-content-center" })
                .append($('<div>', { class: "d-flex d-inline align-items-center align-content-center" })
                    .append($('<img>', { class: "rounded-circle border", src: friend.avatar, alt: "avatar 1", style: "width: 45px; height: 45px;" }),
                        $('<div>', { class: "card-text ms-1" })
                            .append($('<div>', { class: 'text-dark' }).text(friend.username)))),

                $('<div>', { class: "col align-content-center" }).append(friends.crearFormAfegirAmic(friend.id))));



        $element.append($a);
    },
    /**
     * Funció per enviar la sol·licitud d'amistat
     * @param {object} e Event del formulari 
     */
    enviarSolAmic: function (e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        axios
            .post("/enviarSolicitudAmic", formData)
            .then((response) => {
                console.log(response);
                if (e.target.tagName == "formSolAmic") {
                    $(e.target).find("button").attr("disabled", true);
                    $(e.target).find("button").html("Sol·licitud enviada");

                    try {
                        $(`#formAfegirAmic${formData.get('friend_id')}`).find("button").attr("disabled", true);
                        $(`#formAfegirAmic${formData.get('friend_id')}`).find("button").html("Sol·icitud enviada");
                    } catch (err) {
                        console.log(err);
                    }
                } else {
                    $(e.target).find("button").attr("disabled", true);
                    $(e.target).find("button").html("Sol·licitud enviada");

                    $(`#divNotFriend-${formData.get('friend_id')}`).find("button").attr("disabled", true);
                    $(`#divNotFriend-${formData.get('friend_id')}`).find("button").html("Sol·licitud enviada");

                }
                toastAlerts.mostrarToast("success", "Sol·licitud enviada", $(divToasts));
            })
            .catch((error) => {
                console.error(error);
                toastAlerts.mostrarErrors(error.response.data.error, $(divToasts));
            });
    },
    /**
     * Funció per acceptar la sol·licitud d'amistat
     * @param {object} e Event del click
     */
    acceptarSolAmic: function (e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        axios
            .post("/acceptarSolicitudAmic", formData)
            .then((response) => {
                friends.mostrarNouAmic(response.data.user, $('#amics'));
                $(`#solAmic-${response.data.user.id}`).remove();
                toastAlerts.mostrarToast("success", "Sol·licitud aceptada", $(divToasts));
                $(`#divNotFriend-${response.data.user.id}`).remove();
                $(`#solAmics-${response.data.user.id}`).remove();
                notificacions.restarSolAmics();
                try {
                    $(`#divFormFriend${response.data.user.id}`).html('');
                    let form = friends.crearFormEliminarAmic(response.data.user.id);
                    $(`#divFormFriend${response.data.user.id}`).append(form);
                    $(`#totalAmics${response.data.user.id}`).html(parseInt($(`#totalAmics${response.data.user.id}`).html()) + 1);
                } catch (err) {
                    console.log(err);
                }
            })
            .catch((error) => {
                console.log(error);
                toastAlerts.mostrarErrors(error.response.data.error, $(divToasts));
            });
    },
    /**
     * Funció per rebutjar la sol·licitud d'amistat
     * @param {object} e Event del click
     */
    rebutjarSolAmic: function (e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        axios
            .post("/rebutjarSolicitudAmic", formData)
            .then((response) => {
                $(e.target).closest("[name='divSolAmic']").remove();
                notificacions.restarSolAmics();
                toastAlerts.mostrarToast("success", "Sol·licitud rebutjada", $(divToasts));
            })
            .catch((error) => {
                console.error(error);
                toastAlerts.mostrarErrors(error.response.data.error, $(divToasts));
            });
    },

    /**
     * Funció per mostrar les sol·licituds rebudes
     * @param {object} user Usuari que ha enviat la sol·licitud
     */
    mostrarSolicitud: function (user) {
        // Si no hi ha cap sol·licitud, esborrar el text
        notificacions.comprobarNotificacions();

        var $formAcceptarAmic = $('<form>', { name: "formAcceeptarSolAmic", method: "POST", action: "acceptarSolicitudAmic" });
        $formAcceptarAmic.append($('<input>', { type: "hidden", name: "friend_id", value: user.id }));
        $formAcceptarAmic.append($('<button>', { class: "btn btn-primary", type: "submit" }).text("Acceptar"));
        $formAcceptarAmic.on('submit', friends.acceptarSolAmic);

        var $formRebutjarAmic = $('<form>', { name: "formRebutjarSolAmic", method: "POST", action: "rebutjarSolicitudAmic" });
        $formRebutjarAmic.append($('<input>', { type: "hidden", name: "friend_id", value: user.id }));
        $formRebutjarAmic.append($('<button>', { class: "btn btn-danger", type: "submit" }).text("Rebutjar"));
        $formRebutjarAmic.on('submit', friends.rebutjarSolAmic);

        var $div = $('<div>', { class: "col-4", name: "divSolAmic" });
        var $a = $('<a>', { href: `/perfil/${user.id}`, class: "text-decoration-none" });
        $a.append($('<div>', { id: `solAmic-${user.id}`, class: "col-12 text-center p-2 rounded user-hover" })
            .append($('<div>', { class: "user-info" })
                .append(
                    $('<img>', { class: "rounded-circle border", src: user.avatar, alt: "avatar 1", style: "width: 45px; height: 45px;" }),
                    $('<div>', { class: "card-text" })
                        .append($('<div>', { class: "fw-bold text-dark" }).text(user.username)),
                    $formAcceptarAmic,
                    $formRebutjarAmic)));

        $div.append($a);

        $(divSolAmics).append($div);

    },

    /**
     * Funció per eliminar un amic
     * @param {object} e Event del click
     */
    eliminarAmic: function (e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        axios
            .post("/eliminarAmic", formData)
            .then((response) => {
                $(e.target).remove();
                toastAlerts.mostrarToast("success", "Amic eliminat", $(divToasts));
                eliminarAmicHtml(formData.get('friend_id'));
                friends.mostrarNouUsuariNoAmic(response.data.friend, $('#users'));
            })
            .catch((error) => {
                console.error(error);
                toastAlerts.mostrarErrors(error.response.data.error, $(divToasts));
            });
    },
    /**
     * Funció per cercar un usuari
     * @param {object} e Event del teclat
     */
    cercarUsuari: function (e) {
        const search = $(e.target).val();
        if (search.length < 3) {
            return;
        }
        axios
            .get(`/cercarUsuari/${search}`)
            .then((response) => {
                $('#users').html("");
                response.data.users.forEach((user) => {
                    friends.mostrarNouUsuariNoAmic(user);
                });
            })
            .catch((error) => {
                console.error(error);
                toastAlerts.mostrarErrors(error.response.data.error, $(divToasts));
            });}
};

export default friends;


import { chat, authUserAvatar, receiver, divSolAmics, $solAmicsBadge } from './constantChatFriends.js';
import missatges from './chat.js';
import toastAlerts from './alerts.js';
import notificacions from './notificacions.js';
/**
 * Funció per seleccionar un usuari i obrir el xat amb ell
 * @param {object} e Event del click
 * @returns returns quan es fa clic per tancar el chat
 */
export function seleccionarUsuari(e) {
    // Evitar que quan el usuari faci doble click es seleccioni el text
    if (e.detail === 2) e.preventDefault();

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


var friends = {
    /**
     * Funció per mostrar a un nou amic
     * @param {object} friend 
     * @param {jquery} $element 
     */
    mostrarNouAmic: function (friend, $element) {
        var $div = $('<div>', { class: "friend col-3 p-2 justify-content-center position-relative text-center", id: friend.id });
        $div.append($('<div>', { class: "user-info" }).append($('<img>', { class: "rounded-circle", src: friend.avatar, alt: "avatar 1", style: "width: 45px; height: 100%;" }), $('<div>', { class: "card-text" }).append($('<div>', { class: "friend-username" }).text(friend.username), $('<div>', { class: "user-status", id: `status${friend.id}` }).text("Online"))));

        var $badge = $('<span>', { class: "badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle", id: `b-${friend.id}` });
        $badge.hide();
        $badge.html(0);
        $div.append($badge);
        $div.on('click', seleccionarUsuari);
        $element.append($div);
    },
    /**
     * Funció per enviar la sol·licitud d'amistat
     * @param {object} e Event del formulari 
     */
    enviarSolAmic: function (e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        axios
            .post("enviarSolicitudAmic", formData)
            .then((response) => {
                console.log(response);
                $(e.target).find("button").attr("disabled", true);
                $(e.target).find("button").html("Sol·icitud enviada");
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
            .post("acceptarSolicitudAmic", formData)
            .then((response) => {
                friends.mostrarNouAmic(response.data.user, $('#amics'));
                $(`#solAmic-${response.data.user.id}`).remove();
                toastAlerts.mostrarToast("success", "Sol·licitud aceptada", $(divToasts));
                $(`#divNotFriend-${response.data.user.id}`).remove();
                $(`#solAmics-${response.data.user.id}`).remove();
                $('[name="SolAmicsBadge"]').html(parseInt($('[name="SolAmicsBadge"]').html()) - 1);
                if ($('[name="SolAmicsBadge"]').html() == 0) {
                    $('[name="SolAmicsBadge"]').hide();
                    $(divSolAmics).html('<div class="text-center fw-bold">No hi ha sol·licituds de amistat</div>');
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
            .post("rebutjarSolicitudAmic", formData)
            .then((response) => {
                $(e.target).closest(".dropdown-item").remove();
                $('[name="SolAmicsBadge"]').html(parseInt($('[name="SolAmicsBadge"]').html()) - 1);
                if ($('[name="SolAmicsBadge"]').html() == 0) {
                    $('[name="SolAmicsBadge"]').hide();
                    $(divSolAmics).html('<div class="text-center fw-bold">No hi ha sol·licituds de amistat</div>');
                }
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
        if($solAmicsBadge.html() == 1){
            $(divSolAmics).html('');
        }


        var $div = $('<div>', { class: "dropdown-item", id: `solAmic-${user.id}` });
        let form = $('<form>', { name: "formAcceptarSolAmic", action: "acceptarSolicitudAmic", method: "POST" });
        form.append($('<input>', { type: "hidden", name: "friend_id", value: user.id }));
        form.append($('<button>', { class: "btn btn-primary border border-white", type: "submit" }).text("Acceptar"));

        let form2 = $('<form>', { name: "formRebutjarSolAmic", action: "rebutjarSolicitudAmic", method: "POST" });
        form2.append($('<input>', { type: "hidden", name: "friend_id", value: user.id }));
        form2.append($('<button>', { class: "btn btn-danger border border-white", type: "submit" }).text("Rebutjar"));

        $div.append($('<div>', { class: "d-flex justify-content-center" })
            .append($('<img>', { class: "rounded-circle", src: `${user.avatar}`, alt: "avatar 1", style: "width: 45px; height: 100%;" })
                , $('<div>', { class: "ms-2" })
                    .append($('<div>', { class: 'fw-bold' }).text(user.username))
                , form, form2));

        form.on('submit', this.acceptarSolAmic);
        form2.on('submit', this.rebutjarSolAmic);

        $(divSolAmics).append($div);

    },
};

export default friends;


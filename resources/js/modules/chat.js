// import notificacions from "./notificacions";
import { chat, authUserAvatar, userId, form } from "./constantChatFriends";
import { xatAlerts } from "./alerts";
import notificacions from "./notificacions";


/**
 * Funció que crea un missatge enviat per l'usuari actual
 * @param {object} user Objecte amb les dades de l'usuari
 * @param {string} message Missatge enviat
 * @param {*} date Data del missatge
 * @returns {jQuery} Element jQuery amb el missatge creat
 */
function crearMissatgeEnviat(message, date = formatDate(new Date())) {
    let $messageDiv = $('<div>');
    $messageDiv.append($('<div>', { class: "d-flex flex-row justify-content-between" })
        .append($('<p>', { class: "small mb-1" }).html('Tu')
            , $('<p>', { class: "small text-muted" }).html(date)));

    $messageDiv.append($('<div>', { class: "d-flex flex-row justify-content-end" })
        .append($('<img>', { class: "rounded-circle border", src: authUserAvatar, alt: "You", style: "width: 45px; height: 45px;" })
            , $('<div>', { class: "card-text" })
                .append($('<p>', { class: "small p-2 ms-3 mb-3 rounded-3", style: "background-color: #f5f6f7;" }).html(message))));

    return $messageDiv;
};
/**
 * Funció que crea un missatge rebut 
 * @param {object} user Usuari amic que ha enviat el missatge
 * @param {string} message Missatge rebut
 * @param {*} date Data del missatge 
 * @returns {jQuery} Element jQuery amb el missatge creat
 */
function crearMissatgeRebut(user, message, date = formatDate(new Date())) {
    let $messageDiv = $('<div>');
    $messageDiv.append($('<div>', { class: "d-flex flex-row justify-content-between" })
        .append($('<p>', { class: "small mb-1" }).html(user.username)
            , $('<p>', { class: "small text-muted" }).html(date)));

    $messageDiv.append($('<div>', { class: "d-flex flex-row justify-content-start" })
        .append($('<img>', { class: "rounded-circle border", src: user.avatar, alt: user.username, style: "width: 45px; height: 45px;" })
            , $('<div>', { class: "card-text" })
                .append($('<p>', { class: "small p-2 ms-3 mb-3 rounded-3", style: "background-color: #f5f6f7;" }).html(message))));

    return $messageDiv;
};

/**
 * Funció per formatar la data
 * @param {*} date Data a formatar
 * @returns Retorna la data formatejada
 */
function formatDate(date) {
    // comprovar si la data es d'avui
    const d = new Date(date);
    const now = new Date();
    const diffTime = Math.abs(now - d);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    // Si la data es d'avui mostrar l'hora
    if (new Date(d).setHours(0, 0, 0, 0) == now.setHours(0, 0, 0, 0)) {
        return d.toLocaleTimeString("es-ES", {
            hour: "numeric",
            minute: "numeric",
        });
    }
    // Si la data es d'aquest any mostrar el dia i mes
    if (d.getFullYear() == now.getFullYear()) {
        return d.toLocaleDateString("es-ES", {
            month: "long",
            day: "numeric",
            hour: "numeric",
            minute: "numeric",
        });
    }
    // Si la data no es d'aquest any mostrar la data completa
    return d.toLocaleDateString("es-ES", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
    });
}
/**
 * Objecte amb les funcions per mostrar els missatges
 */
var missatges = {
    messages: {},
    /**
     * Funció que mostra un missatge rebut
     * @param {string} missatge Missatge rebut
     * @param {object} user Usuari amic que ha enviat el missatge
     * @param {*} date Data del missatge
     */
    mostrarMissatgeRebut: function (missatge, user, date) {
        let missatgeDiv = crearMissatgeRebut(user, missatge, date);
        $(chat).append(missatgeDiv);
        chat.scrollTop = chat.scrollHeight;
    }
    ,
    /**
     * Funció que mostra un missatge enviat
     * @param {string} missatge Missatge enviat 
     * @param {*} user Usuari que ha enviat el missatge
     * @param {*} date Data del missatge
     */
    mostrarMissatgeEnviat: function (missatge, date) {
        let missatgeDiv = crearMissatgeEnviat(missatge, date);
        $(chat).append(missatgeDiv);
        chat.scrollTop = chat.scrollHeight;
    },
    /**
     * Funció que mostra un conjunt de missatges
     * @param {object} missatges Conjunt de missatges amb la informació del missatge, l'usuari que l'ha enviat i la data 
     * @param {object} user Usuari autenticat
     * @param {object} friend Usuari amic
     */
    mostrarMissatges: function (missatges, friend) {
        for (let missatge of missatges) {
            if (missatge.user_id == userId) {
                this.mostrarMissatgeEnviat(missatge.message, formatDate(missatge.created_at));
            } else {
                this.mostrarMissatgeRebut(missatge.message, friend, formatDate(missatge.created_at));
            }
        }
    },
    /**
    * Funció per marcar els missatges com llegits
    * @param {int} friendId id de l'usuari amic
    */
    marcarMissatgesComLlegits: function (friendId) {
        axios
            .post(`/api/marcar-missatges-llegits`, { friendId })
            .then((response) => {
                console.log(response);
            })
            .catch((error) => {
                console.error(error);
            });
    },
    /**
     * Funció per agafar els missatges de l'usuari amb altre usuari
     * @param {int} friendId id de l'usuari amic
     * @param {string} friendAvatar avatar de l'usuari amic
     * @param {string} friendUsername nom de l'usuari amic
     */
    grabUserMessages: function (friendId, friendAvatar, friendUsername) {
        axios
            .post(`/api/get-user-messages`, { friendId })
            .then((response) => {
                console.log(response);
                // Mostrar els missatges
                missatges.mostrarMissatges(response.data.messages, { id: friendId, avatar: friendAvatar, username: friendUsername });

                notificacions.cleanUserNotifications(friendId);

                // Guardar els missatges a la variable messages
                missatges.messages[friendId] = response.data;
            })
            .catch((error) => {
                console.error(error);
                xatAlerts.mostrarErrors(error.response.data.error);

            });
    },

    /**
     * Funció per enviar un missatge a un usuari
     * @param {object} e Event del formulari
     */
    enviarMissatge: function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        if (formData.get("receiver") == 0) {
            xatAlerts.mostrarErrors("Selecciona un usuari per enviar el missatge");
            return;
        }
        if (formData.get("message").trim() == "") {
            xatAlerts.mostrarErrors("El missatge no pot estar buit");
            return;
        }

        axios
            .post("/api/send-message-to-client", formData)
            .then((response) => {
                console.log(response);
                // Mostrar el missatge enviat
                missatges.mostrarMissatgeEnviat(response.data.message);
                form.reset();
                // Afegir el missatge a la variable messages
                if (missatges.messages[formData.get("receiver")]) {
                    missatges.messages[formData.get("receiver")].messages.push({
                        message: response.data.message,
                        user_id: userId,
                        receiver_id: formData.get("receiver"),
                        created_at: new Date(),
                    });
                }
            })
            .catch((error) => {
                console.error(error);
                xatAlerts.mostrarErrors(error.response.data.error);
            });
    }


};

export default missatges;

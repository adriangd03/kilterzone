// Path: resources/js/home.websocket.js


// Constant del formulari de missatges del xat
const form = document.getElementById("chatForm");
// Constant del xat
const chat = document.getElementById("chat-user");
// Constant del id de l'usuari autenticat
const userId = document.getElementById("userId").value;
// Constant del avatar de l'usuari autenticat
const authUserAvatar = document.getElementById("user_avatar").src;
// Constant del badge de notificacions
const notificacionsBadge = document.getElementById("notificacionsBadge");
// Constant del receiver input del xat
const receiver = document.getElementById("receiver");
// Assignar el valor 0 al receiver input
receiver.value = 0;
// Constant del offcanvas del xat
const offCanvas = document.getElementById("offcanvasChat");
// Constant del canal de xat
const channel = Echo.join(`presence.ChatMessage.${userId}`);
// Constant del canal per els usuaris online, per saber si estan escrivint i per el enviament de solicituds d'amistat
const channel2 = Echo.join('presence.UsersOnline');
// Constant de missatges per guardar els missatges carregats
const messages = {};


// Listener del formulari de missatges del xat
$(form).on("submit", (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    if (formData.get("message").trim() == "") {
        $(chat).append(mostrarError("El missatge no pot estar buit"));
        chat.scrollTop = chat.scrollHeight;
        return;
    }
    if (formData.get("receiver") == 0) {
        $(chat).append(mostrarError("Selecciona un usuari per enviar el missatge"));
        chat.scrollTop = chat.scrollHeight;
        return;
    }

    axios
        .post("/api/send-message-to-client", formData)
        .then((response) => {
            console.log(response);
            showSentMessage(formData.get("message"), new Date());
            form.reset();
            // Afegir el missatge a la variable messages
            if (messages[formData.get("receiver")]) {
                messages[formData.get("receiver")].messages.push({
                    message: formData.get("message"),
                    user_id: userId,
                    receiver_id: formData.get("receiver"),
                    created_at: new Date(),
                });
            }
        })
        .catch((error) => {
            console.error(error.response.data.error);
            mostrarErrors(error.response.data.error, $(chat));
        });
});

// Listener dels divs dels usuaris per seleccionar un usuari i obrir el xat amb ell
$('.user').on('click', seleccionarUsuari);

// Evitar que es seleccioni el text quan es fa doble click
$('.user').on('mousedown', (e) => {
    if (e.detail > 1) {
        e.preventDefault();
    }
});

// Listener del input de missatge
$('#message').on('keyup', (e) => {
    if (e.target.value.trim() == "") {
        channel2.whisper('typing', {
            user_id: userId,
            receiver_id: receiver.value,
            typing: false
        });
        return;
    }
    if (receiver.value == 0) {
        return;
    }
    console.log('typing');
    channel2.whisper('typing', {
        user_id: userId,
        receiver_id: receiver.value,
        typing: true
    });
});

channel
    .here((users) => {
        console.log({ users });
    })
    .listen(".ChatMessage", (event) => {
        console.log("event", event);

        // Comprovar si el chat obert és el mateix que l'usuari que ha enviat el missatge
        if (receiver.value == event.user.id) {
            showReceivedMessage(
                event.message,
                event.user.avatar,
                event.user.username,
                new Date()
            );
            marcarMissatgesComLlegits(event.user.id);
            $('#typing').hide();
        } else {
            // Si no es el mateix usuari, mostrar la notificació
            sumarNotificacionsUser(event.user.id);
        }

        // Afegir el missatge a la variable messages
        if (messages[event.user.id]) {
            messages[event.user.id].messages.push({
                message: event.message,
                user_id: event.user.id,
                receiver_id: userId,
                created_at: new Date(),
            });
        }
    });


channel2
    .here((users) => {
        console.log("subscribed");
        console.log({ users });
        // Afegir la classe online als usuaris que estan connectats
        users.forEach((user) => {
            $(`#status${user.id}`).html("Online");
        });
    })
    .joining((user) => {
        console.log("joining", user);
        $(`#status${user.id}`).html("Online");
    })
    .listenForWhisper('typing', (e) => {
        if (e.receiver_id == userId && e.user_id == receiver.value) {
            if (e.typing) $('#typing').show();
            else $('#typing').hide();
        }
    })
    .leaving((user) => {
        console.log("leaving", user);
        $(`#status${user.id}`).html("Offline");
    });




/**
 * 
 * @param {event} e Event del click
 * @returns returns quan es fa clic per tancar el chat
 */
function seleccionarUsuari(e) {
    // Evitar que quan el usuari faci doble click es seleccioni el text
    if (e.detail === 2) e.preventDefault();

    // Agafar el user div que s'ha clicat
    const userDiv = $(e.target).closest(".user")[0];
    const userId = userDiv.id;
    const userAvatar = $(userDiv).find("img").attr("src");
    const username = $(userDiv).find(".user-name").text();

    // Remoure la classe user-selected de tots els usuaris
    $('.user').removeClass("user-selected");

    // Comparar si el user clicat es el mateix que el user seleccionat
    if (receiver.value == userId) {
        receiver.value = 0;
        $(chat).html("");
        $('#chat-user-name').text("");
        $('#typing').hide();
        return;
    }

    // Afegir la classe user-selected al user clicat
    $(userDiv).addClass("user-selected");

    // Setejar el valor del receiver input amb l'id del user clicat
    $(receiver).val(userId);

    // Mostrar el nom de l'usuari al xat
    $('#chat-user-name').text(username);
    $('#typing').hide();

    // Esborrar tots els missatges del xat
    $(chat).html("");

    // Comprobem si ja hem carregat els missatges d'aquest usuari
    if (messages[userId]) {
        // Mostrar els missatges
        showMessages(messages[userId], userAvatar, username);

        cleanUserNotifications(userId);
    } else {
        // Mostrar els missatges
        grabUserMessages(userId, userAvatar, username);
    }
}


/**
 * Funció per mostrar els missatges
 * @param {object} messages Objecte amb els missatges
 * @param {string} userAvatar Avatar de l'usuari
 * @param {string} username Nom de l'usuari
 */
function showMessages(messages, userAvatar, username) {
    messages.messages.forEach((message) => {
        if (message.user_id == userId) {
            showSentMessage(message.message, message.created_at);
        } else {
            showReceivedMessage(
                message.message,
                userAvatar,
                username,
                message.created_at
            );
        }
    });
}

/**
 * Funció per mostrar els missatges rebuts
 * @param {string} message Missatge rebut
 * @param {string} userAvatar Avatar de l'usuari
 * @param {string} username Nom de l'usuari
 * @param {Date} date Data del missatge
 */
function showReceivedMessage(message, userAvatar, username, date) {
    const messageDiv = $('<div></div>');
    const img = $('<img>', { class: "rounded-circle", src: userAvatar, alt: "avatar 1", style: "width: 45px; height: 100%;" });

    var div = $('<div>', { class: "d-flex justify-content-between" });
    div.append(`<p class="small mb-1">${username}</p>`);
    div.append(`<p class="small mb-1 text-muted">${formatDate(date)}</p>`);
    messageDiv.append(div);

    var div2 = $('<div>', { class: "d-flex flex-row justify-content-start" });
    div2.append(img);
    messageDiv.append(div2);

    var div3 = $('<div>', { class: "card-text" });
    div3.append(`<p class="small p-2 ms-3 mb-3 rounded-3" style="background-color: #f5f6f7;">${message}</p>`);
    div2.append(div3);

    $(chat).append(messageDiv);

    chat.scrollTop = chat.scrollHeight;
}

/**
 * Mostrar missatge enviat per l'usuari
 * @param {string} message Missatge enviat per l'usuari
 * @param {Date} date Data del missatge
 */
function showSentMessage(message, date) {
    const messageDiv = document.createElement("div");
    messageDiv.innerHTML = `
        <div class="d-flex justify-content-between">
            <p class="small mb-1">You</p>
            <p class="small mb-1 text-muted">${formatDate(date)}</p>
        </div>
        <div class="d-flex flex-row justify-content-end">
            <img class="rounded-circle" src="${authUserAvatar}" alt="avatar 1" style="width: 45px; height: 100%;">
            <div class="card-text">
                <p class="small p-2 ms-3 mb-3 rounded-3" style="background-color: #f5f6f7;">${message}</p>
            </div>
        </div>
  `;
    chat.appendChild(messageDiv);
    chat.scrollTop = chat.scrollHeight;
}

/**
 * Funció per agafar els missatges de l'usuari amb altre usuari
 * @param {int} userId id de l'usuari amic
 * @param {string} userAvatar avatar de l'usuari amic
 * @param {string} username nom de l'usuari amic
 */
function grabUserMessages(userId, userAvatar, username) {
    axios
        .post(`/api/get-user-messages`, { userId })
        .then((response) => {
            console.log(response);
            // Mostrar els missatges
            showMessages(response.data, userAvatar, username);

            cleanUserNotifications(userId);

            // Guardar els missatges a la variable messages
            messages[userId] = response.data;
        })
        .catch((error) => {
            console.error(error);
            mostrarErrors(error.response.data.error, $(chat));

        });
}

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
 * Funció per marcar els missatges com llegits
 * @param {int} userId id de l'usuari amic
 */
function marcarMissatgesComLlegits(userId) {
    axios
        .post(`/api/marcar-missatges-llegits`, { userId })
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            console.error(error);
        });
}

/**
 * Funció per netejar les notificacions
 */
function cleanNotifications() {
    notificacionsBadge.style.display = "none";
    notificacionsBadge.innerHTML = 0;
}

/**
 * Funció per netejar les notificacions d'un usuari
 * @param {int} userId id de l'usuari amic
 */
function cleanUserNotifications(userId) {
    let friendBadge = document.getElementById("b-" + userId);
    friendBadge.style.display = "none";

    // Restar el nombre de missatges no llegits al total de notificacions
    notificacionsBadge.innerHTML = parseInt(notificacionsBadge.innerHTML) - parseInt(friendBadge.innerHTML);

    friendBadge.innerHTML = 0;

    if (notificacionsBadge.innerHTML == 0) {
        notificacionsBadge.style.display = "none";
    }
}

/**
 * Funció per sumar el nombre de notificacions al badge de notificacions totals
 */
function sumarNotificacions() {
    notificacionsBadge.innerHTML = parseInt(notificacionsBadge.innerHTML) + 1;
    notificacionsBadge.style.display = "block";
}

/**
 * Funció per augmentar el nombre de notificacions al badge d'un usuari
 * @param {int} userId id de l'usuari amic al que augmentar les notificacions
 */
function sumarNotificacionsUser(userId) {
    let friendBadge = document.getElementById("b-" + userId);
    friendBadge.style.display = "block";
    friendBadge.innerHTML = parseInt(friendBadge.innerHTML) + 1;
    sumarNotificacions();
}

/**
 * Funció per mostrar un missatge d'error
 * @param {string} error Missatge d'error 
 * @returns Retorna un div amb el missatge d'error
 */
function mostrarError(error) {
    let errorDiv = $('<div>', { class: "alert alert-danger alert-dismissible fade show", role: "alert" });
    errorDiv.append(`<strong>Error!</strong> ${error}`);
    errorDiv.append(`<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`);
    return errorDiv;
}

/**
 * Funció per mostrar tots els errors dins d'un objecte
 * @param {object} errors Objecte amb els errors
 * @param {jQuery} $Element Element on mostrar els errors
 */
function mostrarErrors(errors, $Element) {
    for (const error in errors) {
        $Element.append(mostrarError(errors[error]));
        chat.scrollTop = chat.scrollHeight;
    }
}
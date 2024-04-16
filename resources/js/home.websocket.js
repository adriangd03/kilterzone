// Path: resources/js/home.websocket.js

// Constant del formulari i xat
const form = document.getElementById("form");
const chat = document.getElementById("chat-user");
const userId = document.getElementById("userId").value;
const authUserAvatar = document.getElementById("user_avatar").src;
const notificacionsBadge = document.getElementById("notificacionsBadge");
const receiver = document.getElementById("receiver");
const offCanvas = document.getElementById("offcanvasChat");
const channel2 = Echo.join(`presence.ChatMessage.${userId}`);
const messages = {};

channel2
    .here((users) => {
        console.log("subscribed");
        console.log({ users });
    })
    .listen(".ChatMessage", (event) => {
        console.log("event", event);

        // Comprovar si el chat obert és el mateix que l'usuari que ha enviat el missatge
        if (receiver.value == event.user.id) {
            showReceivedMessage(event.message, event.user.avatar, event.user.username, new Date());
        }else{
            // Si no es el mateix usuari, mostrar la notificació
            notificacionsBadge.style.display = "block";
            notificacionsBadge.innerHTML = parseInt(notificacionsBadge.innerHTML) + 1;
        }


        // Afegir el missatge a la variable messages
        if (messages[event.user.id]) {
            messages[event.user.id].messages.push({ message: event.message, user_id: event.user.id, receiver_id: userId, created_at: new Date()});
        }

    });

// Listener del formulari
form.addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    axios
        .post("/api/send-message-to-client", formData)
        .then((response) => {
            console.log(response);
            showSentMessage(formData.get("message"), new Date());
            form.reset();
            // Afegir el missatge a la variable messages
            if (messages[formData.get("receiver")]) {
                messages[formData.get("receiver")].messages.push({ message: formData.get("message"), user_id: userId , receiver_id: formData.get("receiver"), created_at: new Date()});
            }
        })
        .catch((error) => {
            console.error(error);
        });
});

document.querySelectorAll(".user").forEach((user) => {
    user.addEventListener("click", (e) => {
        // Agafar el user div que s'ha clicat
        const userDiv = e.target.closest(".user");
        const userId = userDiv.id;
        const userAvatar = userDiv.querySelector("img").src;
        const username = userDiv.querySelector(".user-name").textContent;

        // Remoure la classe user-selected de tots els usuaris
        document.querySelectorAll(".user").forEach((user) => {
            user.classList.remove("user-selected");
        });

        // Afegir la classe user-selected al user clicat
        userDiv.classList.add("user-selected");

        // Setejar el valor del receiver input amb l'id del user clicat
        receiver.value = userId;

        // Esborrar tots els missatges del xat
        chat.innerHTML = "";

        // Comprobem si ja hem carregat els missatges d'aquest usuari
        if (messages[userId]) {
            // Mostrar els missatges
            showMessages(messages[userId], userAvatar, username);
            return;
        }

        // Mostrar els missatges
        grabUserMessages(userId, userAvatar, username);
    });
});




/**
 * Funció per mostrar els missatges	
 * @param {object} messages 
 */
function showMessages(messages, userAvatar, username) {
    messages.messages.forEach((message) => {
        if (message.user_id == userId) {
            showSentMessage(message.message, message.created_at);
        } else {
            showReceivedMessage(message.message, userAvatar, username, message.created_at);
        }
    });
}

/**
 * Funció per mostrar els missatges rebuts
 * @param {object} event 
 */
function showReceivedMessage(message, userAvatar, username, date) {
    const messageDiv = document.createElement("div");
    const img = document.createElement("img");
    img.src = userAvatar;

    messageDiv.innerHTML = `
          <div class="d-flex justify-content-between">
              <p class="small mb-1">${username}</p>
              <p class="small mb-1 text-muted">${formatDate(date)}</p>
            </div>
            <div class="d-flex flex-row justify-content-start">
              <img class="rounded-circle" src="${userAvatar}"
                alt="avatar 1" style="width: 45px; height: 100%;">
              <div class="card-text">
                <p class="small p-2 ms-3 mb-3 rounded-3" style="background-color: #f5f6f7;">${message}</p>
              </div>
            </div>
    `;

    chat.appendChild(messageDiv);
    chat.scrollTop = chat.scrollHeight;
}

/**
 * Mostrar missatge enviat per l'usuari
 * @param {string} message 
 */
function showSentMessage(message, date) {
    const messageDiv = document.createElement("div");
    messageDiv.innerHTML = `
        <div class="d-flex justify-content-between">
            <p class="small mb-1">You</p>
            <p class="small mb-1 text-muted">${formatDate(date)}</p>
          </div>
          <div class="d-flex flex-row justify-content-end">
          <img class="rounded-circle" src="${authUserAvatar}"
              alt="avatar 1" style="width: 45px; height: 100%;">
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
 * @param {int} userId 
 */
function grabUserMessages(userId, userAvatar, username) {
    axios
        .post(`/api/get-user-messages`, { userId })
        .then((response) => {
            console.log(response);
            // Mostrar els missatges
            showMessages(response.data, userAvatar, username);
            // Guardar els missatges a la variable messages
            messages[userId] = response.data;
        })
        .catch((error) => {
            console.error(error);
        });
}

/**
 * Funció per formatar la data
 * @param {*} date 
 * @returns Retorna la data formatejada
 */
function formatDate(date){
  // comprovar si la data es d'avui
  const d = new Date(date);
  const now = new Date();
  const diffTime = Math.abs(now - d);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  // Si la data es d'avui mostrar l'hora
  if(new Date(d).setHours(0,0,0,0) == now.setHours(0,0,0,0)){
    return d.toLocaleTimeString( 'es-ES', {hour: 'numeric', minute: 'numeric'});
  }
  // Si la data es d'aquest any mostrar el dia i mes
  if(d.getFullYear() == now.getFullYear()){
    return d.toLocaleDateString('es-ES', {month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric'});
  }
  // Si la data no es d'aquest any mostrar la data completa
  return d.toLocaleDateString('es-ES', {year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric'});
}

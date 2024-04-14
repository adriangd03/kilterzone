// Path: resources/js/home.websocket.js

// Constant del formulari i xat
const form = document.getElementById("form");
const chat = document.getElementById("chat-user");
const userId = document.getElementById("userId").value;
// Constant del canal de Echo
// const channel = Echo.join("presence.SendMessageToClientEvent.1");
const channel2 = Echo.join(`presence.ChatMessage.${userId}`);

// Event de subscripció al canal i escolta de l'event
// channel
//     .here((users) => {
//       console.log("subscribed");
//       console.log({ users });
//       showConnectedUsers(users);
//     })
//     .joining((user) => {
//         console.log({ user }, "joining");
//         addUser(user);

//     })
//     .leaving((user) => {
//         console.log({ user }, "leaving");
//         deleteUser(user);

//     })

channel2
    .here((users) => {
        console.log("subscribed");
        console.log({ users });
    })
    .listen(".ChatMessage", (event) => {
        console.log("event", event);

        const message = document.createElement("div");
        const img = document.createElement("img");
        const username = event.user.username;
        img.src = event.user.avatar;

        message.innerHTML = `
          <div class="d-flex justify-content-between">
              <p class="small mb-1">${username}</p>
              <p class="small mb-1 text-muted">23 Jan 2:00 pm</p>
            </div>
            <div class="d-flex flex-row justify-content-start">
              <img class="rounded-circle" src="${event.user.avatar}"
                alt="avatar 1" style="width: 45px; height: 100%;">
              <div class="card-text">
                <p class="small p-2 ms-3 mb-3 rounded-3" style="background-color: #f5f6f7;">${event.message}</p>
              </div>
            </div>
    `;

        chat.appendChild(message);
        chat.scrollTop = chat.scrollHeight;
    });

// Listener del formulari
form.addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    axios
        .post("/api/send-message-to-client", formData)
        .then((response) => {
            console.log(response);
            form.reset();
        })
        .catch((error) => {
            console.error(error);
        });
});

// Listener dels usuaris per obrir xat
document.querySelectorAll(".user").forEach((user) => {
    user.addEventListener("click", (e) => {
        

        // get the user div that was clicked
        const userDiv = e.target.closest(".user");
        const userId = userDiv.id;
        
        


        document.getElementById("receiver").value = userId;

        chat.innerHTML = "";
    });
});

// Show connected users
function showConnectedUsers(users) {
    let usersDiv = document.getElementById("users");
    usersDiv.innerHTML = "";

    users.forEach((user) => {
        let userDiv = document.createElement("div");
        userDiv.innerHTML =
            `<img class="rounded-circle" src="${user.avatar}"
        alt="avatar 1" style="width: 45px; height: 100%;">
      <div class="card-text">` + user.username;
        userDiv.id = user.id;
        usersDiv.appendChild(userDiv);
    });
}

// Add connected users to the list
function addUser(user) {
    let usersDiv = document.getElementById("users");
    let userDiv = document.createElement("div");
    userDiv.innerHTML =
        `<img class="rounded-circle" src="${user.avatar}"
    alt="avatar 1" style="width: 45px; height: 100%;">
  <div class="card-text">` + user.username;
    userDiv.id = user.id;

    usersDiv.appendChild(userDiv);
}

// Delete disconnected users from the list
function deleteUser(user) {
    let userDiv = document.getElementById(user.id);
    userDiv.remove();
}

function showMessages(messages) {
    messages.forEach((message) => {
        const messageDiv = document.createElement("div");
        messageDiv.innerHTML = `
        <div class="d-flex justify-content-between">
            <p class="small mb-1">${message.user.username}</p>
            <p class="small mb-1 text-muted">23 Jan 2:00 pm</p>
          </div>
          <div class="d-flex flex-row justify-content-start">
            <img class="rounded-circle" src="${message.user.avatar}"
              alt="avatar 1" style="width: 45px; height: 100%;">
            <div class="card-text">
              <p class="small p-2 ms-3 mb-3 rounded-3" style="background-color: #f5f6f7;">${message.message}</p>
            </div>
          </div>
  `;
        chat.appendChild(messageDiv);
        chat.scrollTop = chat.scrollHeight;
    });
}

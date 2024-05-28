
import { form, userId, receiver, offCanvasChat, offCanvasUsuaris, channel, channel2 } from './modules/constantChatFriends.js';
import notificacions from './modules/notificacions.js';
import { seleccionarUsuari } from "./modules/friends.js";
import friends from './modules/friends.js';
import missatges from './modules/chat.js';
import toastAlerts from './modules/toastAlerts.js';
import { Offcanvas } from 'bootstrap';

var chatFriends = $(function () {
    // Assignar el valor 0 al receiver input
    receiver.value = 0;

    // Listener dels divs dels usuaris per seleccionar un usuari i obrir el xat amb ell
    $('.friend').on('click', seleccionarUsuari);

    // Listener del formulari de missatges del xat
    $(form).on("submit", missatges.enviarMissatge);

    // Evitar que es seleccioni el text quan es fa doble click
    $('.friend').on('mousedown', (e) => {
        if (e.detail > 1) {
            e.preventDefault();
        }
    });

    offCanvasChat.addEventListener('show.bs.offcanvas', () => {
        // Afegir la classe active al li del xat
        $('#liXat').addClass('active');
        // Treure la classe active del li de usuaris
        $('#liUsuaris').removeClass('active');
    });

    offCanvasChat.addEventListener('hidden.bs.offcanvas', () => {
        // Treure la classe active al li del xat
        $('#liXat').removeClass('active');
    });

    offCanvasUsuaris.addEventListener('show.bs.offcanvas', () => {
        // Afegir la classe active al li de usuaris
        $('#liUsuaris').addClass('active');
        // Treure la classe active del li de xat
        $('#liXat').removeClass('active');
    });

    offCanvasUsuaris.addEventListener('hidden.bs.offcanvas', () => {
        // Treure la classe active al li de usuaris
        $('#liUsuaris').removeClass('active');
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


    // Listener del boto de sol·licitud d'amistat
    $('[name="formSolAmic"]').on('submit', friends.enviarSolAmic);

    // Listener del boto de acceptar sol·licitud d'amistat
    $('[name="formAcceptarSolAmic"]').on('submit', friends.acceptarSolAmic);

    // Listener del boto de rebutjar sol·licitud d'amistat
    $('[name="formRebutjarSolAmic"]').on('submit', friends.rebutjarSolAmic);

    // Listener del formulari de eliminar amic 
    $('#formEliminarAmic').on('submit', friends.eliminarAmic);

    $('#searchUser').on('keyup', friends.cercarUsuari);




    channel
        .here((users) => {
            console.log({ users });
        })
        .listen(".ChatMessage", (event) => {
            console.log("event", event);

            // Comprovar si el chat obert és el mateix que l'usuari que ha enviat el missatge
            if (receiver.value == event.user.id) {
                missatges.mostrarMissatgeRebut(event.message, event.user);
                missatges.marcarMissatgesComLlegits(event.user.id);
                $('#typing').hide();
            } else {
                // Si no es el mateix usuari, mostrar la notificació
                notificacions.sumarNotificacionsUser(event.user.id);
            }
            // Afegir el missatge a la variable messages
            if (missatges.messages[event.user.id]) {
                missatges.messages[event.user.id].messages.push({
                    message: event.message,
                    user_id: event.user.id,
                    receiver_id: userId,
                    created_at: new Date(),
                });
            }
        })
        .listen(".SendFriendRequest", (event) => {
            // Mostrar la notificació de sol·licitud d'amistat
            notificacions.sumarSolAmics();
            // Mostrar la sol·licitud d'amistat
            friends.mostrarSolicitud(event.user);
        }
        )
        .listen(".AcceptFriendRequest", (event) => {
            //Treure la sol·licitud d'amistat i afegir l'amic
            $(`#divNotFriend-${event.user.id}`).remove();
            friends.mostrarNouAmic(event.user, $('#amics'));

            $(`#solAmic-${event.user.id}`).remove();

            // Restar una notificació de sol·licitud d'amistat
            notificacions.restarSolAmics();
            console.log("event", event);
            toastAlerts.mostrarToast("success", "Sol·licitud aceptada", $(divToasts));

            try {
                $(`#divFormFriend${event.user.id}`).html('');
                let form = friends.crearFormEliminarAmic(event.user.id);
                $(`#divFormFriend${event.user.id}`).append(form);
                $(`#totalAmics${event.user.id}`).html(parseInt($(`#totalAmics${event.user.id}`).html()) + 1);
            } catch (err) {
                console.log(err);
            }
        })
        .listen('.RemoveFriend', (event) => {
            friends.eliminarAmicHtml(event.user.id);
            friends.mostrarNouUsuariNoAmic(event.user);
        })
        ;

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



})

export default chatFriends;
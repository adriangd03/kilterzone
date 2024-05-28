
import { chat } from "./constantChatFriends";



var xatAlerts = {
    /**
     * Funció que crea un div amb un missatge d'error
     * @param {string} error  Missatge d'error
     * @returns Element HTML
     */
    mostrarError : function(error) {
        let errorDiv = $('<div>', { class: "alert alert-danger alert-dismissible fade show", role: "alert" });
        errorDiv.append(`<strong>Error!</strong> ${error}`);
        errorDiv.append(`<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`);
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
        return errorDiv;
    },
    /**
     * Funció que mostra els errors en el xat
     * @param {*} errors objecte o string amb els errors 
     * @returns 
     */
    mostrarErrors : function(errors) {
        if (typeof errors === "string") {
            $(chat).append(this.mostrarError(errors));
            chat.scrollTop = chat.scrollHeight;
            return;
        }
        for (let error in errors) {
            $(chat).append(this.mostrarError(errors[error]));
        }
        chat.scrollTop = chat.scrollHeight;
    },



};

export {xatAlerts};
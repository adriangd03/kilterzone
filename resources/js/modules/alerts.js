
import { chat, divToasts } from "./constantChatFriends";


var toastAlerts = {
    mostrarToast : function(type, message ){
        var $div = $('<div>', { class: `toast show align-items-center text-white bg-primary border-0 bg-${type}`, role: "alert", "aria-live": "assertive", "aria-atomic": "true"});

        $div.append($('<div>', { class: "toast-header" })
        .append($('<strong>', { class: "me-auto" }).text("Notificació")
        , $('<button>', { type: "button", class: "btn-close", "data-bs-dismiss": "toast" })));
    
        $div.append($('<div>', { class: "toast-body" }).text(message));
    
        $(divToasts).append($div);
    
        // Esborrar el toast després de 5 segons
        setTimeout(() => {
            $div.remove();
        }, 5000);
    }
    ,
    mostrarErrors : function(errors){
        if(typeof errors === 'string'){
            this.mostrarToast('danger', errors);
            return;
        }
        for(let error in errors){
            this.mostrarToast('danger', errors[error]);
        }
    }
    
};

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

export default toastAlerts;
export {xatAlerts};
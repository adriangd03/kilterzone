// Constant del div de toasts
export const divToasts = document.getElementById("divToasts");


var toastAlerts = {
    mostrarToast : function(type, message ){
        var $div = $('<div>', { class: `toast show align-items-center  bg-primary border-0 bg-${type}`, role: "alert", "aria-live": "assertive", "aria-atomic": "true"});

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


export default toastAlerts;
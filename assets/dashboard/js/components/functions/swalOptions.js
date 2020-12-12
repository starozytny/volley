function options(title, text, icon='warning', confirmButton="Confirmer", showCancel=true, cancelButton="Annuler"){
    return {
        title: title,
        text: text,
        icon: icon,
        showCancelButton: showCancel,
        confirmButtonText: confirmButton,
        cancelButtonText: cancelButton,
    }
}

module.exports = {
    options
}
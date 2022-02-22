class Notification {
    success() {
        new Noty({
            type: 'success',
            layout: 'topRight',
            text: 'Acción exitosa',
            timeout: 1000,
        }).show();
    }

    alert() {
        new Noty({
            type: 'alert',
            layout: 'topRight',
            text: 'Estas seguro?',
            timeout: 1000,
        }).show();
    }

    error() {
        new Noty({
            type: 'error',
            layout: 'topRight',
            text: 'Datos eliminados!',
            timeout: 1000,
        }).show();
    }

    warning() {
        new Noty({
            type: 'warning',
            layout: 'topRight',
            text: 'Oops ! Algo salió mal',
            timeout: 1000,
        }).show();
    }

    imageValidation() {
        new Noty({
            type: 'warning',
            layout: 'topRight',
            text: 'Imagen, menos de 1 MB',
            timeout: 2000,
        }).show();
    }


}
export default Notification = new Notification();
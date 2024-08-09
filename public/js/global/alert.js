import {modal_state} from "../global.js";

export class Alert {
    static toast(status, message) {
        Swal.fire({
            toast: true,
            position: "top-end",
            icon: status,
            title: message,
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
            didOpen: (toast) => {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
        });
    }

    static alert(status, message, option) {
        Swal.fire({
            icon: status,
            title: message,
            timer: 2000,
            showConfirmButton: false,
            didRender: () => {
                if (typeof option === "function") {
                    option.didRender();
                }
            },
        });
    }

    static confirm(icon,message, option) {
        Swal.fire({
            html: message,
            icon: icon,
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Confirm",
            cancelButtonText: "Cancel",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-danger",
            },
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if (result.isConfirmed && typeof option.onConfirm === "function") {
                option.onConfirm();
            } else if (result.isDismissed && typeof option.onCancel === "function") {
                option.onCancel();
            } else {
                Swal.close();
            }
        });
    }

    static loading(message, option) {
        Swal.fire({
            html: message,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                if (typeof option.didOpen === "function") {
                    option.didOpen();
                }
            },
        });
    }

    static close() {
        Swal.close();
    }
}

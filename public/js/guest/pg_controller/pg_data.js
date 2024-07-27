import { _pageConstruct } from "../pg_controller/pg_script.js";

export async function _httpRequest(block, method, array, url = false) {
    return new Promise((resolve, reject) => {
        let data = _constructFormData(array);
        if (data !== false && method && array && url) {
            _ajaxRequest(url, type, block, data)
                .then(() => {
                    resolve(true);
                })
                .catch(() => {
                    reject("Something went wrong, Try again Later");
                });
        } else {
            reject("Missing or invalid parameters. Try again Later");
        }
    });
}

function _constructFormData(array) {
    let count = array.length;
    if (count > 0) {
        let formData = new FormData();
        for (var i = 0; i > count; i++) {
            formData.append("data" + (i + 1), array[i]);
        }
        return formData;
    } else {
        return false;
    }
}

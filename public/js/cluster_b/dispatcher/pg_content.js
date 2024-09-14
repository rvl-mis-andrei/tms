'use strict';

import { RequestHandler } from "../../global/request.js";
import {construct_url} from "../../global.js";

export function page_content(page, param=null) {
    return new Promise((resolve, reject) => {
        let formData = new FormData();
        formData.append("page", page);
        if (param !== false) {  formData.append("id", param);  }
        (new RequestHandler()).post(construct_url("tms/cco-b/dispatcher/setup-page"), formData)
            .then((response) => {
                if (page) {
                    let tt = page.replace(/[^A-Z0-9]+/ig, " ");
                    let url = window.location.pathname;
                    $("head > title").empty().append("Trip Monitoring | " + (tt.charAt(0).toUpperCase() + tt.slice(1)).split('/')[0]);
                    $('.page-heading').text((tt.charAt(0).toUpperCase() + tt.slice(1)).split('/')[0]);

                    if (param !== null && param !== false) {
                        if (url.split('/')[4] !== page) {
                            window.history.pushState(null, null, page + '/' + param);
                        }
                    } else {
                        if (url.split('/')[5] !== null && typeof url.split('/')[5] !== 'undefined') {
                            window.history.pushState(null, null, '../' + page);
                        } else {
                            window.history.pushState(null, null, page);
                        }
                    }
                    app.empty().append(response.page);
                    resolve(true);
                } else {
                    app.empty().append(pageNotFound());
                    resolve(false);
                }
            })
            .catch((err) => {
                console.log(err);
                reject(err);
            })
            .finally(() => {
                $("#main_modal").modal("hide");
                $(document.body).removeClass("modal-open").css({ overflow: "", "padding-right": "" });
                $(".modal-backdrop").remove();
            });
    });
}

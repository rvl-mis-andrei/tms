'use strict';

import { RequestHandler } from "../../global/request.js";

export function construct_url(url) {
    var root = window.location.protocol + "//" + window.location.host;
    return root + "/" + url;
}

export function page_state(container, status,val=false){
    var html = "";
    var initial_state = container[0].innerHTML;

    switch (status) {

        case 'is_empty':
        html = `<div id="empty_state_wrapper" >
                <div class="card-px text-center pt-15 pb-15">
                    <h2 class="fs-2x fw-bold mb-0" id="empty_state_title">Nothing in here</h2>
                    <p class="text-gray-400 fs-4 fw-semibold py-7" id="empty_state_subtitle">
                        No results found
                    </p>

                </div>
                <div class="text-center pb-15 px-5">
                    <img src="../assets/media/illustrations/sketchy-1/16.png" alt="" class="mw-100 h-200px h-sm-325px">
                </div>
            </div>`;
            container.html(html)
        break;

        case 'is_loading':
            html = `<div id="load_state_wrapper" class=" d-flex justify-content-center flex-column" style="position: relative">
                        <div class="card-px text-center pt-15 pb-15">
                            <h2 class="fs-2x fw-bold mb-0" id="load_state_title" style="position: absolute; left: 40%;">Searching ...</h2>
                            <p class="text-gray-400 fs-4 fw-semibold py-7 mt-5" id="load_state_subtitle">
                                This may take time please wait.
                            </p>

                        </div>
                        <div class="text-center pb-15 px-5">
                            <img src="../assets/media/illustrations/sketchy-1/5.png" alt="" class="mw-100 h-200px h-sm-325px">
                        </div>
                    </div>`;
            container.html(html)
        break;

        case 'initial':
            html = `<div id="initial_state_wrapper" class="d-flex justify-content-center flex-column">
                        <div class="card-px text-center pt-15 pb-15">
                            <h2 class="fs-2x fw-bold mb-0" id="initial_state_title">Search</h2>
                            <p class="text-gray-400 fs-4 fw-semibold py-7" id="initial_state_subtitle">
                                Please enter a keyword or phrase to search for.
                            </p>

                        </div>
                        <div class="text-center pb-15 px-5">
                            <img src="../assets/media/illustrations/sketchy-1/5.png" alt="" class="mw-100 h-200px h-sm-325px">
                        </div>
                    </div>`;
            container.html(html);
        break;

        case 'empty-table':
            html = `<div id="initial_state_wrapper" class="d-flex justify-content-center flex-column">
                        <div class="card-px text-center pt-15 pb-15">
                            <h2 class="fs-2x fw-bold mb-0" id="initial_state_title">${val}</h2>
                            <p class="text-gray-400 fs-4 fw-semibold py-7" id="initial_state_subtitle">
                                Please select a keyword or phrase to search for.
                            </p>

                        </div>
                    </div>`;
            container.html(html);
         break;

        case 'page_not_found':
            html = `<div class="container d-flex justify-content-center align-items-center flex-column" style="width: 100%; height: 70vh;">
                        <h1 style="font-size: 250px; font-weight: 900; color: rgba(151, 151, 151, 0.395)">404</h1>
                        <h6 style="font-size: 30px; color: rgba(151, 151, 151, 0.395)">Page not found</h6>
                    </div>`;
            container.html(html);
        break;

        default:
            container.html(initial_state);
        break;
    }
}

export function page_content(page, param) {

    let formData = new FormData();
    formData.append("page", page);

    if(param != false){  formData.append("id", param) }

    return (new RequestHandler()).post(construct_url("tms/cco-b/dispatcher/setup-page"), formData)
        .then((response) => {
            if (page) {
                let tt = page.replace(/[^A-Z0-9]+/ig," ");
                let url = window.location.pathname;
                $("head > title").empty().append("Trip Monitoring | "+(tt.charAt(0).toUpperCase() + tt.slice(1)).split('/')[0]);
                if (param !== null && param !== false) {
                    if(url.split('/')[4] !== page){  window.history.pushState(null, null,page+'/'+param);  }
                }else{
                    if(url.split('/')[5] !== null && typeof url.split('/')[5] !== 'undefined'){
                        window.history.pushState(null, null, '../'+page);
                    }else{
                        window.history.pushState(null, null, page);
                    }
                }
                app.empty().append(response.page);
            } else {
                app.empty().append(pageNotFound());
            }
            return response;
        })
        .catch((err) => console.log(err))
        .finally(() => {
            $("#main_modal").modal("hide");
            $(document.body).removeClass("modal-open").css({ overflow: "", "padding-right": "" });
            $(".modal-backdrop").remove();
        });

}

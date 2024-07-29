'use strict';

export function page_state(container, status,val=false){
    var html = "";
    var initial_state = container[0].innerHTML;

    switch (status) {

        case 'empty':
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

        case 'loading':
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

        case 'not_found':
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

export function construct_url(url) {
    var root = window.location.protocol + "//" + window.location.host;
    return root + "/" + url;
}

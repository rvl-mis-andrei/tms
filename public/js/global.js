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
                    <img src="${asset_url+'/media/illustrations/sketchy-1/16.png'}" alt="" class="mw-100 h-200px h-sm-325px">
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
                            <img src="${asset_url+'/media/illustrations/sketchy-1/5.png'}" alt="" class="mw-100 h-200px h-sm-325px">
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
                            <img src="${asset_url+'/media/illustrations/sketchy-1/5.png'}" alt="" class="mw-100 h-200px h-sm-325px">
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

export function modal_state(modal_id,action='hide'){

    let modal = bootstrap.Modal.getOrCreateInstance(document.querySelector(modal_id));

    if(action == 'show'){
        modal.show();
    }else if(action == 'hide'){
        modal.hide();
        $('.modal-backdrop').remove()
    }
}

export function draw_table(id, container){
    var table = `<div class="table-responsive" id="table_wrapper">
                    <table class="table table-striped align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="${id}"></table>
                </div>`;
    container.html(table);
}

export function construct_url(url) {
    var root = window.location.protocol + "//" + window.location.host;
    return root + "/" + url;
}

export function data_bs_components()
{
    let formSelect = $('.form-select').not('.modal-select');
    if (formSelect.length > 0) {
        formSelect.select2();
    }
    let select2Modal = $('.modal-select[data-control="select2"]');
    if (select2Modal.length > 0) {
        select2Modal.select2({
            dropdownParent: $('.modal')
        });
    }
    let tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    if (tooltipTriggerList.length > 0) {
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }

    let popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    if (popoverTriggerList.length > 0) {
        [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
    }

    let flatPickrList = document.querySelectorAll('input[input-control="flatpicker"]');
    if (flatPickrList.length > 0) {
        $(flatPickrList).flatpickr({
            // defaultDate: new Date(),
            dateFormat: 'm-d-Y',
        });
    }


}

export function fv_validator(){
    return {validators:{notEmpty:{message:'This field is required'}}};
}

export function custom_upload() {
    const uploadAreas = document.querySelectorAll('.uploadArea');
    const fileInputs = document.querySelectorAll('.fileInput');
    const fileNameDisplays = document.querySelectorAll('.fileName');
    const uploadTexts = document.querySelectorAll('.uploadText');
    const removeFileButtons = document.querySelectorAll('.removeFile');

    uploadAreas.forEach((uploadArea, index) => {
        const fileInput = fileInputs[index];
        const fileNameDisplay = fileNameDisplays[index];
        const uploadText = uploadTexts[index];
        const removeFileButton = removeFileButtons[index];

        uploadArea.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                fileNameDisplay.textContent = `Selected file: ${[...fileInput.files].map(f => f.name).join(', ')}`;
                uploadText.style.display = 'none';
                removeFileButton.style.display = 'inline-block';
            } else {
                resetUploadArea(fileNameDisplay, uploadText, removeFileButton);
            }
        });

        removeFileButton.addEventListener('click', (e) => {
            e.stopPropagation();
            fileInput.value = '';
            resetUploadArea(fileNameDisplay, uploadText, removeFileButton);
        });
    });

    function resetUploadArea(fileNameDisplay, uploadText, removeFileButton) {
        fileNameDisplay.textContent = '';
        uploadText.style.display = 'block';
        removeFileButton.style.display = 'none';
    }
}


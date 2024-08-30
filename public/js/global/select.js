'use strict';

import {RequestHandler} from './request.js';


export async function trigger_select(select)
{
    return new Promise((resolve) => {
        $.each(select, function(element, text) {
            let found = false, val=null;
            $(element).find('option').each(function() {
                if ($(this).text() === text) {
                    val = $(this).val();
                    found = true;
                    return false;
                }
            });
            $(element).val(val).trigger('change');
        });
    });
}

export async function location(param) {
    return new Promise((resolve, reject) => {
        let element = $(`select[name="location"]`);
        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/services/select/location", formData)
            .then((res) => {
                element.empty().append(res);
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function tractor(param='') {
    return new Promise((resolve, reject) => {
        let element = $(`select[name="tractor"]`);
        element.attr('disabled', true);

        let formData = new FormData();
        // console.log(param)
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/services/select/tractor", formData)
            .then((res) => {
                element.empty().append(res);
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);

            });
    });
}


export async function trailer(param='') {
    return new Promise((resolve, reject) => {
        let element = $(`select[name="trailer"]`);
        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/services/select/trailer", formData)
            .then((res) => {
                element.empty().append(res);
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function cluster_driver(param='') {
    return new Promise((resolve, reject) => {
        let element = $(`select.cluster_drivers`);
        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/services/select/cluster_drivers", formData)
            .then((res) => {
                element.empty().append(res);
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function dealer(param='') {
    return new Promise((resolve, reject) => {
        let element = $(`select[name="dealer"]`);
        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/services/select/dealer", formData)
            .then((res) => {
                element.empty().append(res);
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function car_model(param='') {
    return new Promise((resolve, reject) => {
        let element = $(`select[name="model"]`);
        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        element.select2({
            ajax: {
                url: '/services/select/car_model', // Replace with your API endpoint
                dataType: 'json',
                delay: 250,
                type: 'POST',
                data: function(params) {
                    return {
                        search: params.term, // The search term entered by the user
                        page: params.page || 1 // Pagination page, if you want to handle pagination
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.name // Adjust this to your data structure
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Search for an option',
            minimumInputLength: 3,
            dropdownParent: $('.modal')
        });
        element.attr('disabled', false);
    });
}

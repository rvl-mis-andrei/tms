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

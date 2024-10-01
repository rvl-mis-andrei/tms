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

export async function dealer(param='',modal_id=false) {
    return new Promise((resolve, reject) => {
        let element = $(`select[name="dealer"]`);
        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/services/select/dealer", formData)
            .then((res) => {
                element.empty().append(res);

                element.select2({
                    dropdownParent: modal_id ? $(modal_id) : null,
                    width: '100%',
                    tags:true,
                    allowClear: true,
                    createTag: function(params) {
                        var term = $.trim(params.term);

                        if (term === '') {
                            return null; // Return null if the term is empty
                        }

                        return {
                            id: term, // Create new option
                            text: term, // Display text for the option
                            newOption: true // Mark as a new option
                        };
                    },
                    templateResult: function(data) {
                        // Highlight the new tag that the user typed
                        var $result = $('<span></span>');
                        $result.text(data.text);

                        if (data.newOption) {
                            $result.append(' <em>(new)</em>');
                        }

                        return $result;
                    }
                });

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

export async function car_model(param='',modal_id=false) {
    return new Promise((resolve, reject) => {
        let element = $(`select[name="model"]`);
        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        element.select2({
            tags: true,
            allowClear: true,
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
            dropdownParent: modal_id ? $(modal_id) : null,
            placeholder: 'Search for an option',
            minimumInputLength: 3,
            width: '100%',
            createTag: function (params) {
                var term = $.trim(params.term);

                // Check if the term is empty or already exists in the options
                if (term === '') {
                    return null;
                }

                // Create a new option that will be treated as a custom entry
                return {
                    id: term, // Set id to the custom value
                    text: term, // Set the displayed text to the custom value
                    newTag: true // This helps in identifying if it's a new custom tag
                };
            },
            templateSelection: function (data) {
                // Highlight newly created tags if needed
                return data.newTag ? data.text : data.text;
            }
        });
        element.attr('disabled', false);
    });
}

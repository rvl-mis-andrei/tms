'use strict';

import {RequestHandler} from './request.js';


export async function trigger_select(select, text) {
    return new Promise((resolve) => {
        $(select).each(function() {
            let val = null;
            $(this).find('option').each(function() {
                if ($(this).text() === text) {
                    val = $(this).val();
                    return false; // Break out of the loop
                }
            });
            $(this).val(val).trigger('change'); // Set the value and trigger change
        });
        resolve(); // Resolve the promise after processing
    });
}

export async function location(param) {
    return new Promise((resolve, reject) => {
        let element = $(`select[name="location"]`);
        let modal = element.closest('.modal');
        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/services/select/location", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
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

export async function tractor(param='') {
    return new Promise((resolve, reject) => {
        let element = $(`select[name="tractor"]`);
        let modal = element.closest('.modal');
        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/services/select/tractor", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
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

export async function trailer(param='') {
    return new Promise((resolve, reject) => {
        let element = $(`select[name="trailer"]`);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/services/select/trailer", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
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

export async function trailer_type(param='') {
    return new Promise((resolve, reject) => {
        let element = $(`select[name="trailer_type"]`);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/services/select/trailer_type", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
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

export async function cluster_driver(param='') {
    return new Promise((resolve, reject) => {
        let element = $(`select.cluster_drivers`);
        let modal = element.closest('.modal');
        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/services/select/cluster_drivers", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
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

export async function trailer_driver(param='') {
    return new Promise((resolve, reject) => {
        let element = $(`select[name="trailer_driver"]`);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/services/select/trailer_driver", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
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

import {page_content} from '../pg_controller/pg_content.js';
import {_httpRequest} from '../pg_controller/pg_data.js';
import {_fvNewTrainingVideos} from '../fv_controller/0021.js';
import {gs_Select2,gs_Quill,gs_FilePond} from '../../global.js';

export function controller(view,pg){

    _fvNewTrainingVideos();

    gs_Select2()
    gs_Quill('#description');
    gs_FilePond('.filepond');

    KTMenu.createInstances();
    KTImageInput.init();
}

export function construct(res,type){

    switch(type){

    }

}

import toastAlerts from "./modules/alerts";
let peces = [];
let start = 0;
let end = 0;
let layout = $('#layout').val();
let wall = $('#size').val();
const homeWallSizes = {
    '7x10FullRideLedKitHomeWall': {
        'svg': $('[name="7x10FullRideLedKitHomeWall"]'),
    },
    '7x10MainlineLedKitHomeWall': {
        'svg': $('[name="7x10MainlineLedKitHomeWall"]'),
    },
    '7x10AuxiliaryLedKitHomeWall': {
        'svg': $('[name="7x10AuxiliaryLedKitHomeWall"]'),
    },
    '10x10FullRideLedKitHomeWall': {
        'svg': $('[name="10x10FullRideLedKitHomeWall"]'),
    },
    '10x10MainlineLedKitHomeWall': {
        'svg': $('[name="10x10MainlineLedKitHomeWall"]'),
    },
    '10x10AuxiliaryLedKitHomeWall': {
        'svg': $('[name="10x10AuxiliaryLedKitHomeWall"]'),
    },
};

$(function () {

    // comprovar si el layout es homeWall
    if (layout === 'homeWall') {
        console.log('homeWall');
        // Comprovem el valor de la variable wall per mostrar el svg correcte de homeWallSizes
        switch (wall) {
            case '7x10FullRideLedKitHomeWall':
                homeWallSizes['7x10FullRideLedKitHomeWall']['svg'].removeClass('d-none');
                break;
            case '7x10MainlineLedKitHomeWall':
                homeWallSizes['7x10MainlineLedKitHomeWall']['svg'].removeClass('d-none');
                break;
            case '7x10AuxiliaryLedKitHomeWall':
                homeWallSizes['7x10AuxiliaryLedKitHomeWall']['svg'].removeClass('d-none');
                break;
            case '10x10FullRideLedKitHomeWall':
                homeWallSizes['10x10FullRideLedKitHomeWall']['svg'].removeClass('d-none');
                break;
            case '10x10MainlineLedKitHomeWall':
                homeWallSizes['10x10MainlineLedKitHomeWall']['svg'].removeClass('d-none');
                break;
            case '10x10AuxiliaryLedKitHomeWall':
                homeWallSizes['10x10AuxiliaryLedKitHomeWall']['svg'].removeClass('d-none');
                break;
            
            
        }
    }


    $('#size').on('change', function () {
        $(`[name="${wall}"]`).addClass('d-none');
        wall = $(this).val();
        $(`[name="${wall}"]`).removeClass('d-none');
        // Unselect all the paths
        $('path').css('stroke', 'black');
        start = 0;
        end = 0;
        peces = [];
    });



    //We add a listener to all the paths of the svg
    $('path').on('click', function () {
        console.log('click');
        // We get the id of the path
        var id = $(this).attr('id');

        // If the path is already selected, we remove it from the array and change the border color to black
        if (peces.some(e => e.id === id)) {
            let peca = peces.find(e => e.id === id);

            if (peca.tipus !== 'end') {
                if (peca.tipus === 'foot') {
                    if (end < 2) {
                        $(this).css('stroke', 'red');
                        $(this).css('stroke-width', '5');
                        peca.tipus = 'end';
                        end = end + 1;
                    } else {
                        $(this).css('stroke', 'black');
                        $(this).css('stroke-width', '1');
                        peces = peces.filter(e => e.id !== id);
                        end = end - 1;
                    }
                }
                if (peca.tipus === 'middle') {
                    $(this).css('stroke', 'orange');
                    $(this).css('stroke-width', '5');
                    peca.tipus = 'foot';
                }
                if (peca.tipus === 'start') {
                    $(this).css('stroke', 'blue');
                    $(this).css('stroke-width', '5');
                    peca.tipus = 'middle';
                    start = start - 1;
                }
            } else {
                $(this).css('stroke', 'black');
                $(this).css('stroke-width', '1');
                peces = peces.filter(e => e.id !== id);
                end = end - 1;
                return;
            }
        } else {
            // Comprovem que no hi hagi 2 peces de tipus start
            if (start < 2) {
                // Change the border color of the path to red
                $(this).css('stroke', 'yellow');
                $(this).css('stroke-width', '5');
                // We add the id to the array
                peces.push({ id: id, tipus: 'start' });
                start = start + 1;
            } else {
                $(this).css('stroke', 'blue');
                $(this).css('stroke-width', '5');
                peces.push({ id: id, tipus: 'middle' });
            }
        }
    });

    $('#formCrearRuta').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('peces', JSON.stringify(peces));
        axios.post('/crearRuta', formData)
            .then(function (response) {
                console.log(response);
                if (response.data.success) {
                    alert('Ruta creada correctament');
                } else {
                    alert('Error al crear la ruta');
                }
            })
            .catch(function (error) {
                console.log(error);
                console.log(error.response.data.error);

                // Esborrar els errors anteriors
                $('.alert').addClass('d-none');
                $('.form-control').removeClass('is-invalid');

                // Mostrar els errors
                if (typeof error.response.data.error === 'string') {
                    toastAlerts.mostrarToast('danger', error.response.data.error);
                    return;
                }
                for (let err in error.response.data.error) {
                    $(`#${err}Alert`).removeClass('d-none').text(error.response.data.error[err]);
                    $(`#${err}`).addClass('is-invalid');
                }

            });
    });


    // axios.interceptors.request.use(function (config) {

    //     config.metadata = { startTime: new Date() }
    //     return config;
    // }, function (error) {
    //     return Promise.reject(error);
    // });


    // axios.interceptors.response.use(function (response) {

    //     response.config.metadata.endTime = new Date()
    //     response.duration = response.config.metadata.endTime - response.config.metadata.startTime
    //     return response;
    // }, function (error) {
    //     error.config.metadata.endTime = new Date();
    //     error.duration = error.config.metadata.endTime - error.config.metadata.startTime;
    //     return Promise.reject(error);
    // });

});
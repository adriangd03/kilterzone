import toastAlerts from "./modules/alerts";
let peces = [];
let start = 0;
let end = 0;
let layout = $('#layout').val();
let wall = '';
const homeWallSizes = {
    '7x10FullRideLedKitHomeWall': {
        'svg': $('[name="7x10FullRideLedKitHomeWall"]'),
        'value': '7x10FullRideLedKitHomeWall',
        'text': '7x10 Full Ride LED kit'
    },
    '7x10MainlineLedKitHomeWall': {
        'svg': $('[name="7x10MainlineLedKitHomeWall"]'),
        'value': '7x10MainlineLedKitHomeWall',
        'text': '7x10 Mainline LED kit'
    },
    '7x10AuxiliaryLedKitHomeWall': {
        'svg': $('[name="7x10AuxiliaryLedKitHomeWall"]'),
        'value': '7x10AuxiliaryLedKitHomeWall',
        'text': '7x10 Auxiliary LED kit'
    },
    '10x10FullRideLedKitHomeWall': {
        'svg': $('[name="10x10FullRideLedKitHomeWall"]'),
        'value': '10x10FullRideLedKitHomeWall',
        'text': '10x10 Full Ride LED kit'
    },
    '10x10MainlineLedKitHomeWall': {
        'svg': $('[name="10x10MainlineLedKitHomeWall"]'),
        'value': '10x10MainlineLedKitHomeWall',
        'text': '10x10 Mainline LED kit'
    },
    '10x10AuxiliaryLedKitHomeWall': {
        'svg': $('[name="10x10AuxiliaryLedKitHomeWall"]'),
        'value': '10x10AuxiliaryLedKitHomeWall',
        'text': '10x10 Auxiliary LED kit'
    },
    '8x12FullRideLedKitHomeWall': {
        'svg': $('[name="8x12FullRideLedKitHomeWall"]'),
        'value': '8x12FullRideLedKitHomeWall',
        'text': '8x12 Full Ride LED kit'
    },
    '8x12MainlineLedKitHomeWall': {
        'svg': $('[name="8x12MainlineLedKitHomeWall"]'),
        'value': '8x12MainlineLedKitHomeWall',
        'text': '8x12 Mainline LED kit'
    },
    '8x12AuxiliaryLedKitHomeWall': {
        'svg': $('[name="8x12AuxiliaryLedKitHomeWall"]'),
        'value': '8x12AuxiliaryLedKitHomeWall',
        'text': '8x12 Auxiliary LED kit'
    },
    '10x12FullRideLedKitHomeWall': {
        'svg': $('[name="10x12FullRideLedKitHomeWall"]'),
        'value': '10x12FullRideLedKitHomeWall',
        'text': '10x12 Full Ride LED kit'
    },
    '10x12MainlineLedKitHomeWall': {
        'svg': $('[name="10x12MainlineLedKitHomeWall"]'),
        'value': '10x12MainlineLedKitHomeWall',
        'text': '10x12 Mainline LED kit'
    },
    '10x12AuxiliaryLedKitHomeWall': {
        'svg': $('[name="10x12AuxiliaryLedKitHomeWall"]'),
        'value': '10x12AuxiliaryLedKitHomeWall',
        'text': '10x12 Auxiliary LED kit'
    },
};
const originalSizes = {
    '7x10BoltOnsScrewOns': {
        'svg': $('[name="7x10BoltOnsScrewOns"]'),
        'value': '7x10BoltOnsScrewOns',
        'text': '7x10 Bolt Ons & Screw Ons'
    },
    '7x10BoltOns': {
        'svg': $('[name="7x10BoltOns"]'),
        'value': '7x10BoltOns',
        'text': '7x10 Bolt Ons'
    },
    '7x10ScrewOns': {
        'svg': $('[name="7x10ScrewOns"]'),
        'value': '7x10ScrewOns',
        'text': '7x10 Screw Ons'
    },
    '8x12BoltOnsScrewOns': {
        'svg': $('[name="8x12BoltOnsScrewOns"]'),
        'value': '8x12BoltOnsScrewOns',
        'text': '8x12 Bolt Ons & Screw Ons'
    },
    '8x12BoltOns': {
        'svg': $('[name="8x12BoltOns"]'),
        'value': '8x12BoltOns',
        'text': '8x12 Bolt Ons'
    },
    '8x12ScrewOns': {
        'svg': $('[name="8x12ScrewOns"]'),
        'value': '8x12ScrewOns',
        'text': '8x12 Screw Ons'
    },
    '12x12BoltOnsScrewOns': {
        'svg': $('[name="12x12BoltOnsScrewOns"]'),
        'value': '12x12BoltOnsScrewOns',
        'text': '12x12 Bolt Ons & Screw Ons'
    },
    '12x12BoltOns': {
        'svg': $('[name="12x12BoltOns"]'),
        'value': '12x12BoltOns',
        'text': '12x12 Bolt Ons'
    },
    '12x12ScrewOns': {
        'svg': $('[name="12x12ScrewOns"]'),
        'value': '12x12ScrewOns',
        'text': '12x12 Screw Ons'
    },
    '12x14BoltOnsScrewOns': {
        'svg': $('[name="12x14BoltOnsScrewOns"]'),
        'value': '12x14BoltOnsScrewOns',
        'text': '12x14 Bolt Ons & Screw Ons'
    },
    '12x14BoltOns': {
        'svg': $('[name="12x14BoltOns"]'),
        'value': '12x14BoltOns',
        'text': '12x14 Bolt Ons'
    },
    '12x14ScrewOns': {
        'svg': $('[name="12x14ScrewOns"]'),
        'value': '12x14ScrewOns',
        'text': '12x14 Screw Ons'
    },
    '16x12BoltOnsScrewOns': {
        'svg': $('[name="16x12BoltOnsScrewOns"]'),
        'value': '16x12BoltOnsScrewOns',
        'text': '16x12 Bolt Ons & Screw Ons'
    },
    '16x12BoltOns': {
        'svg': $('[name="16x12BoltOns"]'),
        'value': '16x12BoltOns',
        'text': '16x12 Bolt Ons'
    },
    '16x12ScrewOns': {
        'svg': $('[name="16x12ScrewOns"]'),
        'value': '16x12ScrewOns',
        'text': '16x12 Screw Ons'
    },
};

function seleccionarPeca(e) {
    var id = $(this).attr('id');

    // If the path is already selected, we remove it from the array and change the border color to black
    if (peces.some(e => e.id === id)) {
        let peca = peces.find(e => e.id === id);

        if (peca.tipus !== 'end') {
            if (peca.tipus === 'foot') {
                if (end < 2) {
                    $(this).css('stroke', 'red');
                    $(this).css('stroke-width', '2');
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
                $(this).css('stroke-width', '2');
                peca.tipus = 'foot';
            }
            if (peca.tipus === 'start') {
                $(this).css('stroke', 'blue');
                $(this).css('stroke-width', '2');
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
            $(this).css('stroke-width', '2');
            // We add the id to the array
            peces.push({ id: id, tipus: 'start' });
            start = start + 1;
        } else {
            $(this).css('stroke', 'blue');
            $(this).css('stroke-width', '2');
            peces.push({ id: id, tipus: 'middle' });
        }
    }
}

function afegirOptionsHomeWall() {
    for (let size in homeWallSizes) {
        $('#size').append(`<option value="${homeWallSizes[size]['value']}">${homeWallSizes[size]['text']}</option>`);
    }
}

function afegirOptionsOriginal() {
    for (let size in originalSizes) {
        $('#size').append(`<option value="${originalSizes[size]['value']}">${originalSizes[size]['text']}</option>`);
    }
}

$(function () {

    // comprovar si el layout es homeWall
    if (layout === 'homeWall') {
        // Afegim les opcions de les mides de les parets de homeWall
        afegirOptionsHomeWall();
        wall = '7x10FullRideLedKitHomeWall';
        // Mostrem la mida de la paret seleccionada
        $(`[name="${wall}"]`).removeClass('d-none');

    
    } else if (layout === 'original') {
        // Comprovem que les options estiguin afegides
        $('#size').empty();
        afegirOptionsOriginal();
        wall = '7x10BoltOnsScrewOns';
        // Mostrem la mida de la paret seleccionada
        $(`[name="${wall}"]`).removeClass('d-none');
    

    } else {
        $('svg').addClass('d-none');
        toastAlerts.mostrarToast('danger', 'No s\'ha trobat el layout, si us plau, selecciona un layout.');
    }


    $('#size').on('change', function () {
        $(`[name="${wall}"]`).addClass('d-none');
        wall = $(this).val();
        $(`[name="${wall}"]`).removeClass('d-none');
        // Unselect all the paths
        $('path').css('stroke', 'black');
        $('circle').css('stroke', 'black');
        $('path').css('stroke-width', '1');
        $('circle').css('stroke-width', '1');
        start = 0;
        end = 0;
        peces = [];
    });

    $('#layout').on('change', function () {
        $(`[name="${wall}"]`).addClass('d-none');
        layout = $(this).val();
        $('#size').empty();
        if (layout === 'homeWall') {
            afegirOptionsHomeWall();
            wall = '7x10FullRideLedKitHomeWall';
        } else if (layout === 'original') {
            afegirOptionsOriginal();
            wall = '7x10BoltOnsScrewOns';
        } else {
            toastAlerts.mostrarToast('danger', 'No s\'ha trobat el layout, si us plau, selecciona un layout.');
            wall = "";
        }

        $(`[name="${wall}"]`).removeClass('d-none');
        // Unselect all the paths
        $('path').css('stroke', 'black');
        $('circle').css('stroke', 'black');
        $('path').css('stroke-width', '1');
        $('circle').css('stroke-width', '1');
        start = 0;
        end = 0;
        peces = [];
    });



    //We add a listener to all the paths of the svg
    $('path').on('click', seleccionarPeca);
    $('circle').on('click', seleccionarPeca);

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
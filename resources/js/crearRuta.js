let peces = [];
let start = 0;
let end = 0;

$(function () {

    $('#auxiliary').prop('checked', false);
    $('#mainline').prop('checked', true);

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
                console.log(error.response.data.errors);
            });
    });

});
$(document).ready(function (){
    $('#init-db').on('click',function (){
        $(this).hide();
        $('#spinner').show();
        init_db();
    })


});


function init_db(){
    fetch('/init')
        .then(response => {
            if (!response.ok) {
                $('#init-db').show();
                $('#spinner').hide();
                throw new Error('Error ' + response.statusText);
            }
            return response.json();

        })
        .then(data=>{
           console.log(data);
           let characters_count = data['characters_count'];
           let locations_count = data['locations_count'];
           let episodes_count = data['episodes_count'];
           $("#characters_count").text(`Количество персонажей: ${characters_count}`);
           $("#locations_count").text(`Количество локаций: ${locations_count}`);
           $("#episodes_count").text(`Количество эпизодов: ${episodes_count}`);
            $('#init-db').show();
            $('#spinner').hide();
            getCharacterById(1);

        })

        .catch(error => {
            $('#init-db').show();
            $('#spinner').hide();

            console.error('error send fetch request:', error);
        });
}

function changeCharacter(){
    let id = $('#character-id').val();
    $('#content').empty();
    getCharacterById(id);
}
function getCharacterById(id){
    fetch(`/characters/${id}`)
        .then(response => {
            if (!response.ok) {
                $('#init-db').show();
                $('#spinner').hide();
                throw new Error('Error ' + response.statusText);
            }
            return response.text();

        })
        .then(data=>{
            $('#content').html(data);
            $('#character-id').val(id);
        })

        .catch(error => {
            console.error('error send fetch request:', error);
        });


}

function exportCharacterToExel()
{
    let characterId = $('#character-id').val();
    window.location.href = `/characters/${characterId}/export`;
}

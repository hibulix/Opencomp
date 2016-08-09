//We focus on the first field
var firstVisibleField = $('form').find('input[type=text],textarea,select').filter(':visible:first');
firstVisibleField.focus().select();

//Some sounds to indicate status to user
//noinspection JSUnresolvedFunction
var successSound = new Audio('/img/success.mp3');
//noinspection JSUnresolvedFunction
var errorSound = new Audio('/img/error.mp3');
//noinspection JSUnresolvedFunction
var completedSound = new Audio('/img/completed.mp3');

//Fields that we're using
var pupilIdField = $('#pupil-id');
var resultsFields = $('.result');
var cancelButton = $('#cancel');

//Blocks that we're using
var resultsBlock = $("#results-input");
var pupilBlock = $("#pupil-input");

var baseURL =  $('#base_url').text();

var evaluation;
var pupil_id;

//When user is on pupil field and Enter Key or Tab Key is pressed
//we cancel the event and check if entered pupil id is valid
pupilIdField.on( 'keydown', function( e ) {
    var charCode = e.which || e.keyCode;
    var sweetAlertOpened = $('.showSweetAlert').filter(':visible').length;

    if(charCode == 13 && sweetAlertOpened){
        e.preventDefault();
        sweetAlert.close();
    }
    if (charCode == 9 || charCode == 13) {
        e.preventDefault();
        checkPupil(pupilIdField);
    }
} );

//When user leave field without using Enter Key or Tab Key
//we  check if entered pupil id is valid
pupilIdField.on('blur', function() {
    checkPupil(pupilIdField);
} );

//If Enter Key is pressed (or sended by barcode scanner)
//we cancel the event and emulate Tab Key instead
resultsFields.on('keydown', function(e) {
    var charCode = e.which || e.keyCode;

    if (charCode == 13) {
        e.preventDefault();
        $.emulateTab();
    }
} );

//When user leave a result field, we check if result is valid
resultsFields.blur(function(event) {
    checkResult(event);
});


cancelButton.on('click', function(){
    resetLayout();
});

//noinspection JSUnusedGlobalSymbols
/**
 * This function display pupils results on the page
 *
 * @param results Results formatted that way [{"competence_id": 946, "pupil_id": 128, "result": "B"},{},{}]
 */
function loadResults(results){

    for (var key in results) {
        if(results.hasOwnProperty(key)){
            var result = results[key].result;
            //noinspection JSUnresolvedVariable
            var id = results[key].competence_id + '_' + results[key].pupil_id;
            var result_elmt = $('#'+id);
            result_elmt.text(result);

            setResultColor(result_elmt);
        }
    }
    $('#loading').hide();
    $('#loaded').show();
    setTimeout(function() {
        $("#loaded").fadeOut();
    }, 5000);
}

function setResultColor(elmt){
    //Color mapping according to results
    var colors = { "a" : "success", "b" : "info", "c": "warning", "d": "danger", "ne": "dark", "abs": "dark" };

    var result = elmt.text().toLowerCase();
    var color = "label-" + colors[result];

    elmt.removeClass().addClass('badge').addClass(color);
}

function animateResult(elmt,effect){
    setResultColor(elmt);
    elmt.addClass('animated '+effect);
    setTimeout(function(){
        elmt.removeClass('animated '+effect);
    }, 1000);
}

/**
 * Checks that pupil_id passed in the field exists
 * 
 * @param pupilIdField The field containing the pupil id
 */
function checkPupil(pupilIdField){
    pupil_id = parseInt(pupilIdField.val());

    if(!isNaN(pupil_id)){
        var pupil = $("#"+pupil_id);
        var pupilExists = pupil.length !== 0;

        if(pupilExists){
            $('#pupil-name').text(pupil.text());
            $("#"+pupil_id).parent().addClass('info');
            pupilBlock.hide();
            loadPupilResults();
            resultsBlock.show();
            var firstVisibleField = resultsBlock.find('input[type=text],textarea,select').filter(':visible:first');
            firstVisibleField.click().focus().select();
        }else{
            pupilIdField.val('').focus();
            errorSound.play();
            sweetAlert("Code élève inconnu", "Le code élève que vous avez scanné " +
                "est incorrect ou l'élève n'a pas passé cette évaluation !", "error");
        }
    }else{
        pupilIdField.val('').focus();
    }
}

/**
 * This function resets the layout to the initial state.
 */
function resetLayout() {
    resultsFields.val('').removeClass('success');
    resultsBlock.hide();
    pupilIdField.val('');
    pupilBlock.show({
        "duration": 1,
        "complete": function () {
            pupilIdField.focus().select();
        }
    });
}

function loadPupilResults(){
    $(".result").each(function () {
        var competenceValue = $(this).attr('id');
        $(this).val($("#"+competenceValue+"_"+pupil_id).text());
    });
}

function setPupilResults(){
    $('th').removeClass('success').removeClass('info');
    $("#"+pupil_id).parent().addClass('success');
    $(".result").each(function () {
        var competenceValue = $(this).attr('id');
        var elmt = $("#"+competenceValue+"_"+pupil_id);
        elmt.text($(this).val());
        animateResult(elmt, 'tada');
    });
}

function getResults() {
    var results = {};

    results["Pupils[id]"] = pupil_id;

    var i = 0;
    $(".result").each(function () {
        var competenceValue = $(this).attr('id');
        var resultValue = $(this).val();
        if (resultValue !== '') {
            results["Results["+i+"][competence_id]"] = competenceValue;
            results["Results["+i+"][result]"] = resultValue;
        }
        i++;
    });
    return results;
}

/**
 * Checks if result is valid for current field
 *
 * @param event
 */
function checkResult(event){
    var currentField = $(event.delegateTarget);
    var currentFieldValue = currentField.val();
    var lastField = $('form').find('input[type=text]').filter(':visible:last');
    var currentFieldIsLastField = currentField.attr('id') === lastField.attr('id');

    currentField.removeClass('error').removeClass('success');

    if ($.inArray(currentFieldValue, ["AAA", "QQQ", "A", "a"]) !== -1) {
        currentField.val('A').addClass('success');
    } else if ($.inArray(currentFieldValue, ["BBB", "B", "b"]) !== -1) {
        currentField.val('B').addClass('success');
    } else if ($.inArray(currentFieldValue, ["CCC", "C", "c"]) !== -1) {
        currentField.val('C').addClass('success');
    } else if ($.inArray(currentFieldValue, ["DDD", "D", "d"]) !== -1) {
        currentField.val('D').addClass('success');
    } else if ($.inArray(currentFieldValue, ["NEE", "NE", "ne"]) !== -1) {
        currentField.val('NE').addClass('success');
        currentField.css("background-color", "#e4ffcb");
    } else if ($.inArray(currentFieldValue, ["ABS", "QBS", "abs"]) !== -1) {
        currentField.val('ABS').addClass('success');
    } else if ($.inArray(currentFieldValue, ["", "DEL"]) !== -1) {
        currentField.val('').addClass('success');
    } else {
        currentField.addClass('error');

        if(currentFieldIsLastField){
            setTimeout(function(){
                currentField.focus().select();
            }, 1);
        }else{
            currentField.select();
        }

        errorSound.play();
    }

    if(currentFieldIsLastField && !currentField.hasClass('error')){
        saveResults();
        setPupilResults();
        resetLayout();

        var resultsLeft = $('.badge:empty').length;

        if(resultsLeft === 0){
            completedSound.play();
            swal({
                title: "Beau travail !",
                text: "Vous avez saisi tous les résultats de cette évaluation.",
                type: "success",
                timer: 5000,
                showConfirmButton: true
            });
        }else{
            successSound.play();
        }
    }
}

function saveResults(){
    var results = getResults();
    var endpoint = baseURL + "results/add/" + evaluation.id + ".json";
    $.post(endpoint, results, function(data){});
}
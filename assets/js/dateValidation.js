var updateDays = function (e) {

    var date_input_end = $('input[name="endDate"]'); //our date input has the name "endDate"
    var date_input_start = $('input[name="startDate"]'); //our date input has the name "date"

    var start = date_input_start.val();
    var startDate = new Date(start);
    var end = date_input_end.val();
    var endDate = new Date(end);
    var diff = parseInt((endDate.getTime() - startDate.getTime()) / (24 * 3600 * 1000));

    if (diff < 0) {
        diff = 0;
        $("#alertDaysError").show();
        $('input[name="AddToBasket"]').prop("disabled", true);
    } else {
        $("#alertDaysError").hide();
        $('input[name="AddToBasket"]').prop("disabled", false);
    }

    $("#remainingDays").html(diff);
};

$(document).ready(function () {
    $.fn.datepicker.dates['fr'] = {
        days: ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"],
        daysShort: ["dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam."],
        daysMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
        months: ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"],
        monthsShort: ["janv.", "févr.", "mars", "avril", "mai", "juin", "juil.", "août", "sept.", "oct.", "nov.", "déc."],
        today: "Aujourd'hui",
        monthsTitle: "Mois",
        clear: "Effacer",
        weekStart: 1,
        format: "dd/mm/yyyy"
    };

    var date_input_end = $('input[name="endDate"]'); //our date input has the name "endDate"
    var date_input_start = $('input[name="startDate"]'); //our date input has the name "date"
    date_input_start.on("changeDate", updateDays);
    date_input_end.on("changeDate", updateDays);
});
(function ($) {

    $("button.modify_block").on("click", function () {
        var parent = $(this).parents(".block");
        if (!$(this).hasClass("modify")) {
            $.each(parent.find("table tr"), function (index, element) {

                if ($(this).attr('class').split(' ').length > 1) {
                    var input = "";
                    if ($(this).attr('class').split(' ')[1] == "textarea") {
                        input += "<textarea"
                        input += " name='"+$(this).attr('class').split(' ')[0]+"'>";
                    } else {
                        input += "<input ";
                        input += " name='"+$(this).attr('class').split(' ')[0]+"'";
                        input += " type='"+$(this).attr('class').split(' ')[1]+"'";
                        input += " value ='";
                    }


                    if ($(this).attr('class').split(' ')[1] == "date") {
                        var dateFRarray = $(element).children('td').eq(1).text().split('/');
                        var date = dateFRarray[2] + "-" + dateFRarray[1] + "-" + dateFRarray[0];
                        input +=  date;
                    } else if ($(this).attr('class').split(' ')[1] == "datetime-local") {
                        var splitSpace = $(element).children('td').eq(1).text().split(' ');
                        var dateFRarray = splitSpace[0].split('/');
                        var dateheure = dateFRarray[2] + "-" + dateFRarray[1] + "-" + dateFRarray[0] + "T" + splitSpace[2];
                        input += dateheure;
                    } else {
                        input += $(element).children('td').eq(1).text();
                    }
                    if ($(this).attr('class').split(' ')[1] == "textarea") {
                        input += "</textarea>";
                    }else{
                    input += "' />";
                    }
                }

                $(element).children('td').eq(1).html(input);

            });
            $(this).addClass("modify");
            parent.find(".validate_block").hide();
            $(this).text("Valider");
        } else {
            $(this).removeClass("modify");
            parent.find(".validate_block").show();
            $(this).text("Modifier");
            $.each(parent.find("table tr"), function (index, element) {
                if ($(this).attr('class').split(' ')[1] == "date") {
                    var datearray = $(element).children('td').eq(1).find("input").val().split("-");
                    var dateFR = datearray[2] + "/" + datearray[1] + "/" + datearray[0];
                    $(element).children('td').eq(1).html(dateFR);
                } else if ($(this).attr('class').split(' ')[1] == "datetime-local") {
                    var splitSpace = $(element).children('td').eq(1).find("input").val().split('T');
                    var dateFRarray = splitSpace[0].split('-');
                    var dateheure = dateFRarray[2] + "/" + dateFRarray[1] + "/" + dateFRarray[0] + " à " + splitSpace[1];
                    $(element).children('td').eq(1).html(dateheure);
                } else {
                    $(element).children('td').eq(1).html($(element).children('td').eq(1).find("input,textarea").val());
                }
            });
        }
    });
$("#save").on("click",function (){
    var element = document.getElementById('contract');
    html2pdf(element);
})

})(jQuery);
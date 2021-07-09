(function ($) {

    $("button.modify_block").on("click", function () {
        var parent = $(this).parents(".block");
        if (!$(this).hasClass("modify")) {
            $.each(parent.find("table tr"), function (index, element) {

                if ($(this).attr('class').split(' ').length > 1) {
                    var input = "";
                    if ($(this).attr('class').split(' ')[1] == "textarea") {
                        input += "<textarea"
                        input += " name='" + $(this).attr('class').split(' ')[0] + "'>";
                    } else {
                        input += "<input ";
                        input += " name='" + $(this).attr('class').split(' ')[0] + "'";
                        input += " type='" + $(this).attr('class').split(' ')[1] + "'";
                        input += " value ='";
                    }


                    if ($(this).attr('class').split(' ')[1] == "date") {
                        var dateFRarray = $(element).children('td').eq(1).text().split('/');
                        var date = dateFRarray[2] + "-" + dateFRarray[1] + "-" + dateFRarray[0];
                        input += date;
                    } else if ($(this).attr('class').split(' ')[1] == "datetime-local") {
                        var splitSpace = $(element).children('td').eq(1).text().split(' ');
                        var dateFRarray = splitSpace[0].split('/');
                        var dateheure = dateFRarray[2] + "-" + dateFRarray[1] + "-" + dateFRarray[0] + "T" + splitSpace[2];
                        input += dateheure;
                    } else if ($(this).attr('class').split(' ')[1] == "checkbox") {
                        input += 1;
                    }else{
                        input += $(element).children('td').eq(1).text();
                    }
                    if ($(this).attr('class').split(' ')[1] == "textarea") {
                        input += "</textarea>";
                    }  else if ($(this).attr('class').split(' ')[1] == "checkbox") {
                        if( $(element).children('td').eq(1).text() === "Oui"){
                            input += "' checked />";
                        }else {
                            input += "' />";
                        }
                    }else {
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
                    if($(element).children('td').eq(1).find("input").val() !== "") {
                        var datearray = $(element).children('td').eq(1).find("input").val().split("-");
                        var dateFR = datearray[2] + "/" + datearray[1] + "/" + datearray[0];
                        $(element).children('td').eq(1).html(dateFR);
                        $(element).children('td').eq(2).find("input").attr("value", dateFR);
                    }
                } else if ($(this).attr('class').split(' ')[1] == "datetime-local") {
                    var splitSpace = $(element).children('td').eq(1).find("input").val().split('T');
                    var dateFRarray = splitSpace[0].split('-');
                    var dateheure = dateFRarray[2] + "/" + dateFRarray[1] + "/" + dateFRarray[0] + " à " + splitSpace[1];
                    $(element).children('td').eq(1).html(dateheure);
                    $(element).children('td').eq(2).find("input").val(dateheure)
                } else if ($(this).attr('class').split(' ')[1] == "checkbox") {
                    if($(element).children('td').eq(1).find("input").is(':checked')){
                        $(element).children('td').eq(1).html("Oui");
                        $(element).children('td').eq(2).find("input").val(1);
                    }else{
                        $(element).children('td').eq(1).html("Non");
                        $(element).children('td').eq(2).find("input").val(0)
                    }
                } else {
                    var val = $(element).children('td').eq(1).find("input,textarea").val();
                    $(element).children('td').eq(1).html(val);
                    $(element).children('td').eq(2).find("input").val(val)
                }
            });
        }
    });
})(jQuery);

function callSubmit(url,idContainer){
    var $form = jQuery('<form action="'+url+'" method="POST" enctype="multipart/form-data"></form>');
    jQuery('<input>').attr({
        type: "hidden",
        name: 'action',
        value: 'addContract'
    }).appendTo($form);
    jQuery.each(jQuery("#"+idContainer).find("input[type=hidden]"), function(key, val) {
        jQuery('<input>').attr({
            type: "hidden",
            name: jQuery(val).attr("name"),
            value: jQuery(val).val()
        }).appendTo($form);
    });
    jQuery.each(jQuery(".photoElem"), function(key, val) {
        jQuery(val).appendTo($form);
    });
    $form.appendTo('body').submit();
}

function myCallback(pdf) {
    // Your function, using the pdf object
    //console.log(pdf);
}
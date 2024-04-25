$(document).ready(function () {
    $(".increase").click(function () {
        var add = $(this).parents(".input-number").children("input").val();
        if (add == "") {
            $(this).parents(".input-number").children("input").val(1);
        } else {
            add = Number(add);
            add = add + 1;
            $(this).parents(".input-number").children("input").val(add);
        }
    });

    $(".decrease").click(function () {
        var minus = $(this).parents(".input-number").children("input").val();
        if (minus == "" || minus <= 1) {
            $(this).parents(".input-number").children("input").val(0);
        } else {
            minus = Number(minus);
            minus = minus - 1;
            $(this).parents(".input-number").children("input").val(minus);
        }
    });

    $("#form1").validate({
        onfocusout: false,
        onclick: false,
        rules: {
            meal: {
                required: true,
            },
            number_people: {
                required: true,
                min: 1,
                max: 10,
            },
        },
        messages: {
            meal: {
                required: "The meal field is required.",
            },
            number_people: {
                required: "The number_people field is required.",
                min: "The number_people field must be at least 1.",
                max: "The number_people field must not be greater than 10.",
            },
        },
        errorPlacement: function (error, element) {
            $(element).parent("div").next(".err").html(error);
        },
        submitHandler: function (form) {
            $.ajax({
                url: $(form).attr("action"),
                type: $(form).attr("method"),
                data: $(form).serialize(),
                success: function (response) {
                    switchTabs();
                },
            });

            return false;
        },
    });

    $("#form2").validate({
        onfocusout: false,
        onclick: false,
        rules: {
            restaurant: {
                required: true,
            },
        },
        messages: {
            restaurant: {
                required: "The restaurant field is required.",
            },
        },
        errorPlacement: function (error, element) {
            $(element).parent("div").next(".err").html(error);
        },
        submitHandler: function (form) {
            $.ajax({
                url: $(form).attr("action"),
                type: $(form).attr("method"),
                data: $(form).serialize(),
                success: function (response) {
                    switchTabs();
                },
            });

            return false;
        },
    });

    function switchTabs() {
        var index = $(".tab-content-active").index();

        $(".tab-content").removeClass("tab-content-active");
        $(".nav-item").removeClass("nav-item-active");

        if (index < $(".tab-content").length - 1) {
            $(".tab-content")
                .eq(index + 1)
                .addClass("tab-content-active");
            $(".nav-item")
                .eq(index + 1)
                .addClass("nav-item-active");
        }
    }

    $(".previous").click(function () {
        var index = $(".tab-content-active").index();

        $(".tab-content").removeClass("tab-content-active");
        $(".nav-item").removeClass("nav-item-active");

        if (index <= $(".tab-content").length - 1 && index > 0) {
            $(".tab-content")
                .eq(index - 1)
                .addClass("tab-content-active");
            $(".nav-item")
                .eq(index - 1)
                .addClass("nav-item-active");
        }
    });

    $(".submit").click(function () {
        alert("Congratulations, you have successfully registered");
    });

    $("#form3").submit(function (event) {
        event.preventDefault();
    });

    $(".submit-dishes").click(function () {
        var validate = validateDishes();
        if (validate == true) {
            var allDish = new Array();
            $(".tab3-box").each(function () {
                var dishData = {};
                var dish = $(this).find(".dish").val();
                var ration = $(this).find(".ration").val();
                dishData["dish"] = dish;
                dishData["ration"] = ration;

                allDish.push(dishData);
            });

            var data = new FormData();
            data.append("allDish", JSON.stringify(allDish));

            $.ajax({
                url: "/order/step3",
                type: "POST",
                dataType: "json",
                contentType: false,
                processData: false,
                data: data,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    previewOrder();
                    switchTabs();
                },
            });
        }
    });

    function validateDishes() {
        var check = true;
        var dishes = [];
        $totalRation = 0;
        $(".tab3-box").each(function () {
            var dish = $(this).find(".dish").val();
            var ration = $(this).find(".ration").val();
            $totalRation += Number(ration);

            if (dish == "") {
                check = false;
                $(this).find(".dish").focus();
                $(this).find(".err-dish").html("The dish field is required.");
            } else {
                if (!dishes.includes(dish)) {
                    dishes.push(dish);
                    $(this).find(".err-dish").html("");
                } else {
                    check = false;
                    $(this).find(".dish").focus();
                    $(this)
                        .find(".err-dish")
                        .html("You cannot choose the same dish twice.");
                }
            }
            if (ration == "" || Number(ration) < 1) {
                check = false;
                $(this).find(".ration").focus();
                $(this).find(".err-ration").html("Data ration is invalid");
            } else {
                $(this).find(".err-ration").html("");
            }
        });
        
        if ($totalRation < Number($("#number_people").val())){
            check = false;
            $('.err-total-dishes').html("The number of servings must be greater than or equal to the number of people");
        } else{
            $('.err-total-dishes').html('');
        }

        return check;
    }

    function previewOrder() {
        $.ajax({
            url: "/order/preview",
            type: "POST",
            dataType: "json",
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                var data = response.results;
                var html =
                    `<div class="tab4-box">
                        <div class="tab4-box-title">Meal</div>
                        <div class="tab4-box-content">` +
                    data.meal +
                    `</div>
                        </div>
                        <div class="tab4-box">
                            <div class="tab4-box-title">No of People</div>
                            <div class="tab4-box-content">` +
                    data.number_people +
                    `</div>
                        </div>
                        <div class="tab4-box">
                            <div class="tab4-box-title">Restaurant</div>
                            <div class="tab4-box-content">` +
                    data.restaurant +
                    `</div>
                        </div>
                        <div class="tab4-box">
                            <div class="tab4-box-title">Meal</div>
                            <div class="tab4-box-dishes">`;
                for (var i = 0; i < data.allDish.length; i++) {
                    html +=
                        `<div class="tab4-box-item">
                                   ` +
                        data.allDish[i].dish +
                        ` - ` +
                        data.allDish[i].ration +
                        `
                            </div>`;
                }

                html += `</div>
                        </div>`;
                $(".tab4-body").html(html);
            },
        });
    }
});

function increase(e) {
    var add = $(e).parents(".input-number").children("input").val();

    if (add == "") {
        $(e).parents(".input-number").children("input").val(1);
    } else {
        add = Number(add);
        add = add + 1;
        $(e).parents(".input-number").children("input").val(add);
    }
}

function decrease(e) {
    var minus = $(e).parents(".input-number").children("input").val();
    if (minus == "" || minus <= 1) {
        $(e).parents(".input-number").children("input").val(0);
    } else {
        minus = Number(minus);
        minus = minus - 1;
        $(e).parents(".input-number").children("input").val(minus);
    }
}

function removeContent(e) {
    var count = $(".tab3-box").length;
    if (count > 1) {
        $(e).parents(".tab3-box").remove();
    } else {
        $(".err-dish").html("Invalid operation");
    }
}

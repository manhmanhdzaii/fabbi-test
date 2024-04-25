<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home page</title>

    <link rel="stylesheet" href="{{asset('template/css/reset.css')}}" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('template/css/main.css')}}" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container">
        <!-- Headerrrrr -->
        <div class="header">
            <ul class="navbar-nav">
                <li class="nav-item nav-item-active">
                    Step 1
                </li>
                <li class="nav-item">
                    Step 2
                </li>
                <li class="nav-item">
                    Step 3
                </li>
                <li class="nav-item">
                    Review
                </li>
            </ul>
        </div>
        <!-- End header -->

        <!-- Body -->
        <div class="body">
            <form class="tab-1 tab-content tab-content-active" method="POST" action="/order/step1" id="form1">
                @csrf
                <?php $orderSession = getOrderSession();
                $meals = getListMeals();
                ?>
                <div class="form-input">
                    <div class="box-input">
                        <label for="meal">Please Select a meal</label>
                        <select name="meal" id="meal">
                            <option value="">---</option>
                            @foreach($meals as $key => $value)
                            <option value="{{$value->id}}" <?php echo optional($orderSession)['meal'] == $value->id ? 'selected' : '' ?>>
                                {{$value->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <p class="err"></p>
                </div>
                <div class="form-input">
                    <div class="box-input">
                        <label for="number_people">Please Enter Number of people</label>
                        <div class="input-number">
                            <input name="number_people" type="text" value="{{ optional($orderSession)['number_people'] ?? 1 }}" id="number_people">
                            <div class="change-value">
                                <div>
                                    <i class="fa-solid fa-caret-up increase"></i>
                                </div>
                                <div>
                                    <i class="fa-solid fa-caret-down decrease"></i>
                                </div>
                            </div>
                        </div>
                        <p class="err"></p>
                    </div>
                </div>
                <div class="move-item text-right">
                    <button type="submit">Next</button>
                </div>
            </form>
            <form class="tab-2 tab-content" method="POST" action="/order/step2" id="form2">
                @csrf
                <div class="form-input">
                    <div class="box-input">
                        <label for="restaurant">Please Select a Restaurant</label>
                        <select name="restaurant" id="restaurant">
                            <option value="">---</option>
                            <?php $restaurants = getRestaurantsByMeal(); ?>
                            @foreach($restaurants as $key => $value)
                            <option value="{{$value->id}}" <?php echo optional($orderSession)['restaurant'] == $value->id ? 'selected' : '' ?>>
                                {{$value->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <p class="err"></p>
                </div>
                <div class="move-item d-flex space-between">
                    <button type="button" class="previous">Previous</button>
                    <button type="submit">Next</button>
                </div>
            </form>
            <form class="tab-3 tab-content" method="POST" action="/order/step3" id="form3">
                @csrf
                <?php
                $allDish = optional($orderSession)['allDish'];
                $dishes = getListFoodsByOrder();
                ?>
                <div class="tab3-content">
                    @if(is_array($allDish) && count($allDish) > 0)
                    @foreach($allDish as $key => $item)
                    <div class="tab3-box">
                        <div class="remove-content">
                            <i class="fa-regular fa-trash-can remove-box" onclick="removeContent(this)"></i>
                        </div>
                        <div class="form-input">
                            <div class="box-input">
                                <label for="dish">Please Select a dish</label>
                                <select name="dish" id="dish" class="dish">
                                    <option value="">---</option>
                                    <?php
                                    $dishes = getListFoodsByOrder();
                                    ?>
                                    @foreach($dishes as $key => $value)
                                    <option value="{{$value->id}}" <?php echo optional($item)['dish'] == $value->id ? 'selected' : '' ?>>
                                        {{$value->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="err err-dish"></p>
                        </div>
                        <div class="form-input">
                            <label for="number_people">Please Enter Number of people</label>
                            <div class="input-number">
                                <input name="ration" type="text" value="{{optional($item)['ration'] ?? 1}}" id="ration" class="ration">
                                <div class="change-value">
                                    <div>
                                        <i class="fa-solid fa-caret-up" onclick="increase(this)"></i>
                                    </div>
                                    <div>
                                        <i class="fa-solid fa-caret-down" onclick="decrease(this)"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="err err-ration"></p>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="tab3-box">
                        <div class="remove-content">
                            <i class="fa-regular fa-trash-can remove-box" onclick="removeContent(this)"></i>
                        </div>
                        <div class="form-input">
                            <div class="box-input">
                                <label for="dish">Please Select a dish</label>
                                <select name="dish" id="dish" class="dish">
                                    <option value="">---</option>
                                    <?php $dishes = getListFoodsByOrder(); ?>
                                    @foreach($dishes as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="err err-dish"></p>
                        </div>
                        <div class="form-input">
                            <label for="number_people">Please Enter Number of people</label>
                            <div class="input-number">
                                <input name="ration" type="text" value="1" id="ration" class="ration">
                                <div class="change-value">
                                    <div>
                                        <i class="fa-solid fa-caret-up" onclick="increase(this)"></i>
                                    </div>
                                    <div>
                                        <i class="fa-solid fa-caret-down" onclick="decrease(this)"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="err err-ration"></p>
                        </div>
                    </div>
                    @endif
                </div>
                <p class="err err-total-dishes"></p>
                <div class="more-content">
                    <i id="add-content" class="fa-solid fa-circle-plus"></i>
                </div>
                <div class="move-item d-flex space-between">
                    <button type="button" class="previous">Previous</button>
                    <button type="submit" class="submit-dishes">Next</button>
                </div>
            </form>
            <div class="tab4 tab-content">
                <div class="tab4-body">
                </div>
                <div class="move-item d-flex space-between">
                    <button type="button" class="previous">Previous</button>
                    <button type="button" class="submit">Submit</button>
                </div>
            </div>
        </div>
        <!-- End Body -->

        <!-- Footer -->
        <div class="footer">

        </div>
        <!-- End footer -->
    </div>


    <script type="text/javascript" src="{{asset('template/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('template/js/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('template/js/main.js')}}"></script>
    <script>
        $(document).ready(function() {
            $("#add-content").click(function() {
                $.ajax({
                    url: "order/get-deshes-by-order",
                    type: "POST",
                    dataType: "json",
                    data: {
                        meal: $("#meal").val(),
                        restaurant: $("#restaurant").val()
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function(response) {
                        var dishes = response.results.dishes;

                        var tab3Content = $(".tab3-content");

                        var html = `<div class="tab3-box">
                                        <div class="remove-content">
                                            <i class="fa-regular fa-trash-can remove-box" onclick="removeContent(this)"></i>
                                        </div>
                                        <div class="form-input">
                                            <div class="box-input">
                                                <label for="dish">Please Select a dish</label>
                                                <select name="dish" id="dish" class="dish">
                                                    <option value="">---</option>`;

                        $.each(dishes, function(key, item) {
                            html += `<option value="` + item.id + `">` + item.name +
                                `</option>`;
                        });

                        html += `</select>
                                        </div>
                                        <p class="err err-dish"></p>
                                    </div>
                                    <div class="form-input">
                                        <label for="number_people">Please Enter Number of people</label>
                                        <div class="input-number">
                                            <input name="ration" type="text" value="1" id="ration" class="ration">
                                            <div class="change-value">
                                                <div>
                                                    <i class="fa-solid fa-caret-up" onclick="increase(this)"></i>
                                                </div>
                                                <div>
                                                    <i class="fa-solid fa-caret-down" onclick="decrease(this)"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="err err-ration"></p>
                                    </div>
                         </div>`;

                        tab3Content.append(html);
                    },
                });
            });

            $("#meal").change(function() {
                var valueMeal = $(this).val();
                var valueRestaurant = $('#restaurant').val();
                if (valueMeal == '') return false;
                updateListRestaurants();

                if (valueRestaurant == '') return false;
                updateListDishes();
            });

            $("#restaurant").change(function() {
                var value = $(this).val();

                if (value == '') return false;

                updateListDishes();
            });

            function updateListRestaurants() {
                $.ajax({
                    url: "order/get-restaurants-by-meal",
                    type: "POST",
                    dataType: "json",
                    data: {
                        meal: $("#meal").val()
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function(response) {
                        var restaurants = response.results;
                        var restaurantSelect = $("#restaurant");
                        var valuerestaurantSelect = restaurantSelect.val();

                        restaurantSelect.empty();

                        restaurantSelect.append($('<option></option>').attr('value', '').text('---'))
                        $.each(restaurants, function(key, item) {
                            restaurantSelect.append($('<option></option>').attr('value', item
                                .id).text(item.name));
                        });

                        restaurantSelect.val(valuerestaurantSelect);
                    },
                });
            }

            function updateListDishes() {
                $.ajax({
                    url: "order/get-deshes-by-order",
                    type: "POST",
                    dataType: "json",
                    data: {
                        meal: $("#meal").val(),
                        restaurant: $("#restaurant").val()
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function(response) {
                        var dishes = response.results.dishes;
                        var allDish = response.results.allDish;

                        var tab3Content = $(".tab3-content");

                        if (allDish.length > 0) {
                            tab3Content.html('');

                            for (var i = 0; i < allDish.length; i++) {
                                var html = `<div class="tab3-box">
                                            <div class="remove-content">
                                                <i class="fa-regular fa-trash-can remove-box" onclick="removeContent(this)"></i>
                                            </div>
                                    <div class="form-input">
                                        <div class="box-input">
                                            <label for="dish">Please Select a dish</label>
                                            <select name="dish" id="dish" class="dish">
                                                <option value="">---</option>`;

                                $.each(dishes, function(key, item) {
                                    var checkSelected = item.id == allDish[i]['dish'] ?
                                        'selected' : '';
                                    html += `<option value="` + item.id + `" ` + checkSelected +
                                        `>` + item.name + `</option>`;
                                });

                                html += `</select>
                                        </div>
                                        <p class="err err-dish"></p>
                                    </div>
                                    <div class="form-input">
                                        <label for="number_people">Please Enter Number of people</label>
                                        <div class="input-number">
                                            <input name="ration" type="text" value="` + allDish[i]['ration'] + `" id="ration" class="ration">
                                            <div class="change-value">
                                                <div>
                                                    <i class="fa-solid fa-caret-up" onclick="increase(this)"></i>
                                                </div>
                                                <div>
                                                    <i class="fa-solid fa-caret-down" onclick="decrease(this)"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="err err-ration"></p>
                                    </div>
                                </div>`;

                                tab3Content.append(html);
                            }
                        } else {
                            var html = `<div class="tab3-box">
                                            <div class="remove-content">
                                                <i class="fa-regular fa-trash-can remove-box" onclick="removeContent(this)"></i>
                                            </div>
                                            <div class="form-input">
                                                <div class="box-input">
                                                    <label for="dish">Please Select a dish</label>
                                                    <select name="dish" id="dish" class="dish">
                                                        <option value="">---</option>`;

                            $.each(dishes, function(key, item) {
                                html += `<option value="` + item.id + `">` + item.name +
                                    `</option>`;
                            });

                            html += `</select>
                                    </div>
                                    <p class="err err-dish"></p>
                                </div>
                                <div class="form-input">
                                    <label for="number_people">Please Enter Number of people</label>
                                    <div class="input-number">
                                        <input name="ration" type="text" value="1" id="ration" class="ration">
                                        <div class="change-value">
                                            <div>
                                                <i class="fa-solid fa-caret-up" onclick="increase(this)"></i>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-caret-down" onclick="decrease(this)"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="err err-ration"></p>
                                </div>
                            </div>`;

                            $(".tab3-content").html(html);
                        }
                    },
                });
            }
        });
    </script>
</body>

</html>

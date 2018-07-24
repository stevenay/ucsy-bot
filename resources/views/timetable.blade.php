<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TimeTable</title>

    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/bootstrap.min.css.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/animate.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/select2.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/perfect-scrollbar.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/main.css') }}">
    <!--===============================================================================================-->

</head>
<body>
<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100">
            <div class="table">

                <div class="row header">
                    <div class="cell">
                        Date
                    </div>
                    <div class="cell">
                        9:00 - 9:45
                    </div>
                    <div class="cell">
                        9:45 - 10:30
                    </div>
                    <div class="cell">
                        10:30 - 11:15
                    </div>
                    <div class="cell">
                        11:15 - 12:00
                    </div>
                    <div class="cell">
                       break
                    </div>
                    <div class="cell">
                        12:00 - 12:45
                    </div>
                    <div class="cell">
                        12:45 - 1:30
                    </div>
                </div>

                <div class="row">
                    <div class="cell" data-title="Date">
                        Monday
                    </div>
                    <div class="cell" data-title="9:00 - 9:45">
                        subject 1
                    </div>
                    <div class="cell" data-title="9:45 - 10:30">
                        subject 2
                    </div>
                    <div class="cell" data-title="10:30 - 11:15">
                        subject 3
                    </div>
                    <div class="cell" data-title="11:15 - 12:00">
                        subject 4
                    </div>
                    <div class="cell" data-title="12:00 - 12:45">
                        Break
                    </div>
                    <div class="cell" data-title="12:45 - 1:30">
                        subject 6
                    </div>
                </div>

                <div class="row">
                    <div class="cell" data-title="Date">
                        Tuesday
                    </div>
                    <div class="cell" data-title="9:00 - 9:45">
                        subject 1
                    </div>
                    <div class="cell" data-title="9:45 - 10:30">
                        subject 2
                    </div>
                    <div class="cell" data-title="10:30 - 11:15">
                        subject 3
                    </div>
                    <div class="cell" data-title="11:15 - 12:00">
                        subject 4
                    </div>
                    <div class="cell" data-title="12:00 - 12:45">
                        Break
                    </div>
                    <div class="cell" data-title="12:45 - 1:30">
                        subject 6
                    </div>
                </div>

                <div class="row">
                    <div class="cell" data-title="Date">
                        Wednesday
                    </div>
                    <div class="cell" data-title="9:00 - 9:45">
                        subject 1
                    </div>
                    <div class="cell" data-title="9:45 - 10:30">
                        subject 2
                    </div>
                    <div class="cell" data-title="10:30 - 11:15">
                        subject 3
                    </div>
                    <div class="cell" data-title="11:15 - 12:00">
                        subject 4
                    </div>
                    <div class="cell" data-title="12:00 - 12:45">
                        Break
                    </div>
                    <div class="cell" data-title="12:45 - 1:30">
                        subject 6
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!--===============================================================================================-->
<script src="{{ secure_asset('js/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ secure_asset('js/popper.js') }}"></script>
<script src="{{ secure_asset('js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ secure_asset('js/select2.min.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ secure_asset('js/main.js') }}"></script>

<script>
    window.extAsyncInit = function () {
        // SDK loaded, code to follow
        // the Messenger Extensions JS SDK is done loading
        MessengerExtensions.getSupportedFeatures(function success(result) {
            let features = result.supported_features;
            if (features.indexOf("context") != -1) {
                MessengerExtensions.getContext('353296755145837',
                    function success(thread_context) {
                        // success
                        document.getElementById("psid").value = thread_context.psid;
                        // More code to follow
                    },
                    function error(err) {
                        console.log(err);
                    }
                );
            }
        }, function error(err) {
            console.log(err);
        });
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.com/en_US/messenger.Extensions.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'Messenger'));
</script>
</body>
</html>
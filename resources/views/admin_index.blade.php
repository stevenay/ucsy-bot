<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel</title>

    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/bootstrap/bootstrap.min.css') }}">
    <!--===============================================================================================-->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="{{ secure_asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="{{ secure_asset('css/sb/sb-admin.min.css') }}" rel="stylesheet">

    {{-- Toastr --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

</head>
<body>
<body class="bg-dark">
<div class="container">
    <div class="card card-login mx-auto mt-5">
        <div class="card-header">Originating Message</div>
        <div class="card-body">
            <form id="form-send-message" action="/messages">
                <div class="form-group">
                    <label for="exampleInputEmail1">Choose audience</label>
                    <select name="audience" class="form-control">
                        <option value="student">Student</option>
                        <option value="non-student">Non-student</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Message</label>
                    <textarea name="message_text" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Send Message</button>
            </form>
        </div>
    </div>
</div>

<!--===============================================================================================-->
<script src="{{ secure_asset('js/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ secure_asset('js/popper.js') }}"></script>
<script src="{{ secure_asset('js/bootstrap/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ secure_asset('js/jquery-easing/jquery.easing.min.js') }}"></script>

{{-- Toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    {{-- Send Messenger --}}
    $(document).ready(function () {
        var form = $('#form-send-message');
        var selected = [];

        // toaster config
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "timeOut": "3000"
        }

        form.on("submit", function (e) {
            // showing sending message
            $submitButton = $(this).find('button[type="submit"]');
            $submitButton.html("Sending...");

            // disable all buttons
            $submitButton.prop('disabled', true);

            // sending ajax request
            var postData = $(this).serializeArray();
            var formURL = $(this).attr("action");
            $.ajax({
                url: formURL,
                type: "POST",
                data: postData,
                success: function (data, textStatus, jqXHR) {
                    toastr.success(data.message, 'Success');
                    // showing sending message
                    // $submitButton.html("Send");
                    // $submitButton.prop('disabled', false);
                },
                error: function (jqXHR, status, error) {
                    console.log(jqXHR.status);
                    if (jqXHR.status === 422) {
                        //process validation errors here.
                        var errors = jqXHR.responseJSON;
                        printErrors(errors);
                    }

                },
                complete: function (data) {
                    // showing sending message
                    $submitButton.html("Send");
                    $submitButton.prop('disabled', false);
                }
            });
            e.preventDefault();
        });
    });
</script>

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
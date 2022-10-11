<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        /* desktop view */
        .container {
            font-family: Helvetica, Arial, sans-serif;
            min-width: 1000px;
            overflow: auto;
            line-height: 2;
        }

        .container2 {
            margin: 50px auto;
            width: 70%;
            padding: 20px 0;
        }

        .head {
            border-bottom: 1px solid #eee;
        }

        .head .title {
            font-size: 22.4px;
            color: #00466a;
            text-decoration: none;
            font-weight: 600;
        }

        .user {
            font-size: 17.6px;
        }

        .otp {
            background: #00466a;
            margin: 0 auto;
            width: max-content;
            padding: 0 10px;
            color: #fff;
            border-radius: 4px;
        }

        .regards {
            font-size: 14.4px;
        }

        hr {
            border: none;
            border-top: 1px solid #eee;
        }

        .company {
            float: right;
            padding: 8px 0;
            color: #aaa;
            font-size: 12px;
            line-height: 1;
            font-weight: 300;
        }

        /* tab view */
        @media only screen and (max-width: 768px) {
            .container {
                max-width: 768px;
            }

            .container2 {
                margin: 50px auto;
                width: 70%;
                padding: 20px 0;
            }
        }

        /* mobile view */
        @media only screen and (max-width: 425px) {
            .container {
                max-width: 768px;
            }

            .container2 {
                margin: 50px auto;
                width: 70%;
                padding: 20px 0;
            }

            .container2 p {
                font-size: 30px;
            }

            .head .title {
                font-size: 50px;
            }

            .user {
                font-size: 30px;
            }

            .regards {
                font-size: 30px;
            }

            .company {
                font-size: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="container2">
            <div class="head">
                <a href="" class="title">Plexus</a>
            </div>
            <p class="user">Hi, {{$user}}</p>
            <p>
                We have received your request to reset your account password.You can use the following code to recover your account: 
            </p>
            <h2 class="otp">{{$code}}</h2>
            <p>The allowed duration of the code is 30 minutes from the time the message was sent</p>
            <p class="regards">Regards,<br />Plexus</p>
            <hr />
            <div class="company">
                <p>Plexus</p>
                <p>Pasteur</p>
                <p>Bandung</p>
            </div>
        </div>
    </div>

</body>

</html>
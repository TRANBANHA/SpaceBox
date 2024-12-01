<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $title ?? 'SpaceBox' }}</title>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="{{ url('favicon.ico')}} " type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <!-- chat -->
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
        
        <link rel="stylesheet" href="{{ asset('assets/css/components/header.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/components/footer.css') }}">

        {{ $linkcss ?? '' }}


        <style>
            *{
                margin: 0;
                border: 0;
                box-sizing: border-box;

            }

            body{
                font-family: 'Poppins', sans-serif;
            }


            .flex-col{
                display: flex;
                flex-direction: column;
            }
            .flex-row{
                display: flex;
                flex-direction: row;
            }

            #myLayout{
                height: 100vh; 
            }
            

        </style>
    </head>
    <body>

        <section id="myLayout" class="flex-col">
            {{ $slot }}
        </section>
        
    </body>
</html>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Chat Web</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        
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
                font-family: Arial, Helvetica, sans-serif;
            }


            .flex-col{
                display: flex;
                flex-direction: column;
            }
            .flex-row{
                display: flex;
                flex-direction: row;
            }

            
        </style>
    </head>
    <body>
        <section id="content">
            <h1>Admin</h1>
        </section>
    </body>
</html>
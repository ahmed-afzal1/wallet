<!DOCTYPE html>
<html  lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Goadmin Filetree</title>
	<!-- bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- css -->
    <style>
        .filetree{
            background: #f8f8f8;
        }
        .filetree ul{
            padding:0;
            margin-left:40px;
            border-left: 1px dashed #000;
        }
        .filetree ul{
            list-style-type: none;
        }

    </style>

</head>
<body>


    <!-- main-area  -->
    <section class="container pb-5 main-area">
        <div class="row">

            <!-- info -->
            <div class="col-12 offset-sm-1 col-sm-10">
                <div class="card">
                    <div class="card-body pb-5">

                        <h5 class="card-title pt-4">File Structure</h5>
                        <div class="filetree py-4 mb-3">
                            <ul>
                                <li>
                                    --- view/
                                    <ul>
                                        <li>
                                            --- auth
                                            <ul>
                                                <li>
                                                    --- admin/
                                                    <ul>
                                                        <li>--- forgot</li>
                                                        <li>--- login </li>
                                                        <li>--- register </li>
                                                    </ul>
                                                </li>
                                                <li>--- invalid</li>
                                                <li>--- login</li>
                                            </ul>
                                        </li>
                                        <li>--- backend/
                                            <ul>
                                                <li>--- admin/
                                                    <ul>
                                                        <li>--- dashboard</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            --- userpanel/
                                            <ul>
                                                <li>--- dashboard</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>

                        </div>


                    </div>
                </div>
            </div>

        </div>
    </section>
</body>
</html>
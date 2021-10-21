<!doctype html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>فقه الحياة</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="/v1/css/styleSheet.css">
    <!-- Font Awesome JS -->
    <script src="https://kit.fontawesome.com/00a20bfa02.js" crossorigin="anonymous"></script>
    <!-- Axios Library -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- iPhone icon -->
    <link type="image/png" href="/v1/images/HikmaPicture.png" rel="apple-touch-icon">
    <link rel="icon" type="image/png" href="/v1/images/HikmaPicture.png">

</head>
<style>
    /* h1 {
        font-size: 464%;
    } */

    /* i {
        font-size: 214%;
    } */

    img {
        width: 282px;
        height: 282px;
        object-fit: cover;
        object-position: 20% 10%;
    }

    .division0 {
        /* background: #d9d9d9; */
        margin: 5%;
        padding: 2%;
        text-align: center;
    }

    .division1 {
        background: white;
        margin: 2%;
    }

    .division2 {
        text-align: right;
        margin-top: 0%;
        margin-bottom: 0%;
        margin-left: 1%;
        margin-right: 1%;
    }

    .nav-item {
        padding-right: 10px;
    }
</style>

<body>
    <a class="floating share-link me-3" data-action="share/whatsapp/share"><i class="fab fa-whatsapp fa-2x" style="color:black" aria-hidden="true"></i></a>
    <p class="number-of-messages"></p>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>جميع التصنيفات</h3>
            </div>
            <ul class="list-unstyled components">
                <!-- <p>تصفح التصنيفات</p> -->
            </ul>
        </nav>
        <button id="xmark" type="button" aria-label="Close" class="btn-close"></button>
        <!-- Sidebar  -->
        <!-- Page Content  -->
        <!-- <button class="btn btn-info" type="button" id="sidebarCollapse"><i class="fas fa-align-left" aria-hidden="true"></i></button> -->
        <div id="content">
            <header>
                <div class="jumbotron text-center" style="margin-bottom: 0px;">
                    <h1>فقه الحياة</h1>
                    <p>مقولات الدكتور عبدالعزيز فيصل المطوع</p>
                    <form method="post" action="/v1/php/endSession.php"><button class="logout-button" type="submit" style="margin:auto;display:none;">تسجيل الخروج</button></form>
                </div>
                <nav class="navbar navbar-expand-lg navbar-light bg-light">

                    <div class="container-fluid">
                        <button class="btn btn-info" type="button" id="sidebarCollapse" style="background-color: #9bafca; border-color: #9bafca;"><i class="fas fa-align-left" aria-hidden="true"></i></button>
                        <a class="navbar-brand" href="/home">الرئيسية</a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon" aria-hidden="true"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                                <!-- <li class="nav-item">
                                    <a href="/categories" class="nav-link active" aria-current="page">التصنيفات</a>
                                </li> -->
                                <li class="nav-item">
                                    <a href="/about" class="nav-link active" aria-current="page">عن الموقع</a>
                                </li>
                            </ul>
                            <form class="form-inline me-2 me-lg-0" action="/search" method="GET" name="search">
                                <div class="search-form"><input class="form-control ms-sm-2" type="search" placeholder="أدخل كلمة البحث" aria-label="بحث" name="searchText"><button class="btn btn-outline-primary " type="submit">بحث</button></div>
                            </form>
                        </div>
                    </div>
                </nav>
                <div class="container">
                    <div class="row justify-content-between mt-2">
                        <div class="col-8 col-2-md counter"></div>
                        <div class="font-size-controls col-3 col-2-md">

                        </div>
                        <h3 class="category-name"></h3>

                        <p style="display: inline;" class="category-number"></p>
                    </div>
                </div>
            </header>
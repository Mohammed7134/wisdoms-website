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
    .floating {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        clip-path: circle(50%);
        background-color: rgb(221, 255, 221);
    }

    .number-of-messages {
        position: fixed;
        bottom: 35px;
        right: 19px;
        width: 20px;
        height: 20px;
        clip-path: circle(50%);
        background-color: white;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

<body>
    <a class="floating share-link me-3" data-action="share/whatsapp/share"><i class="fab fa-whatsapp fa-2x" style="color:green" aria-hidden="true"></i></a>
    <p class="number-of-messages"></p>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>جميع التصنيفات</h3>
            </div>
            <ul class="list-unstyled components">
                <p>تصفح التصنيفات</p>
            </ul>
        </nav>
        <button id="xmark" type="button" aria-label="Close" class="btn-close"></button>
        <!-- Sidebar  -->
        <!-- Page Content  -->
        <div id="content">
            <header>
                <div class="jumbotron text-center" style="margin-bottom: 0px;">
                    <h1>فقه الحياة</h1>
                    <p></p>
                    <a class="login-link" href="/login">سجل الدخول</a>
                    <form method="post" action="/v1/php/endSession.php"><button class="logout-button" type="submit" style="margin:auto;display:none;">تسجيل الخروج</button></form>

                </div>
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid"><button class="btn btn-info" type="button" id="sidebarCollapse"><i class="fas fa-align-left" aria-hidden="true"></i></button><a class="navbar-brand" href="/home">الرئيسية</a><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="fas fa-search" aria-hidden="true"></span></button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item"></li>
                            </ul>
                            <form class="form-inline me-2 me-lg-0" action="/search" method="GET" name="search">
                                <div class="search-form"><input class="form-control ms-sm-2" type="search" placeholder="أدخل كلمة البحث" aria-label="بحث" name="searchText"><button class="btn btn-outline-success " type="submit">بحث</button></div>
                            </form>
                        </div>
                    </div>
                </nav>
                <div class="container">
                    <div class="row justify-content-between mt-2">
                        <div class="col-8 col-2-md counter"></div>
                        <div class="font-size-control col-3 col-2-md">
                            <div class="btn-group" role="group" aria-label="font size control"><button type="button" class="btn btn-outline-primary plus">+</button><button type="button" class="btn btn-outline-primary minus">-</button></div>
                        </div>
                    </div>
                </div>
            </header>
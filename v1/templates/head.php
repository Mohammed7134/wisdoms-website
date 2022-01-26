<?php
function headerTemplate($title, $noindex)
{
    if ($noindex) {
        $noindexTag = "<meta name=”robots” content=”noindex”>";
    } else {
        $noindexTag = "";
    }
    echo <<< HTML
    <!doctype html>
    <html dir="rtl" lang="ar">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>$title</title>
        $noindexTag
        <meta name="description" content="مفاهيم وتوجيهات تتعلق بالحياة"/>
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
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-176019485-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'G-H598W69820');
        const whatsAppShared = gtag('event', "share_click", {"event_category" : "share", "event_label" : "shared via WhatsApp"});
        </script>
    </head>
    <body>
        <button id="btnScrollToTopId"><i class="fas fa-arrow-up"></i></button>
        <a class="floating share-link me-3" data-action="share/whatsapp/share"><i class="fab fa-whatsapp fa-2x" style="color:black" aria-hidden="true"></i></a>
        <div id="snackbar"></div>
        <p class="number-of-messages"></p>
        <div class="wrapper">
            <nav id="sidebar">
                <div class="sidebar-header">
                    <h3>التصنيفات</h3>
                </div>
                <ul class="list-unstyled components">
                    <!-- <p>تصفح التصنيفات</p> -->
                </ul>
            </nav>
            <button id="xmark" type="button" aria-label="Close" class="btn-close"></button>
            <!-- Sidebar  -->
            <!-- Page Content  -->
            <div id="content">
                <header>
                    <div class="jumbotron text-center" style="margin-bottom: 0px;">
                        <h1>فقه الحياة</h1>
                        <p>مقولات الدكتور عبدالعزيز فيصل المطوع</p>
                        <a href="/add/add?newWisdom=true"><button class="new-wisdom-button" type="submit" style="margin:10px auto;display:none;">إضافة حكمة</button></a>
                    </div>
                    <nav class="navbar navbar-expand-lg navbar-light bg-light">
                        <div class="container-fluid">
                            <button class="btn " type="button" id="sidebarCollapse" style="background-color: #9bafca; "><i class="fas fa-align-left" aria-hidden="true"></i></button>
                            <a class="navbar-brand" href="/">الرئيسية</a>
                            <button class="navbar-toggler btn" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fas fa-search"></i>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                                    <li class="nav-item">
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
                            <div class="font-size-controls col-4 col-2-md" style="display: none;">
                                <label for="customRange1" class="form-label">حجم الخط</label>
                                <input type="range" class="form-range" id="customRange1" min="0" max="10" value="0">
                            </div>
                            <h3 class="category-name"></h3>

                            <p style="display: inline;" class="category-number"></p>
                        </div>
                    </div>
                </header>
    HTML;
};

<?php
session_start();
include("v1/templates/head.php"); ?>
<div class="container">
    <div class="row py-2">
        <div class="col">
            <div class="card" style="width: 8.5rem; text-align: center;">
                <a href="/explore?categoryId=1464" class="m-3">
                    <i class="fas fa-book-open card-img-top fa-6x"></i>
                    <div class="card-body">
                        <p class="card-text">قصص</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col">
            <div class="card" style="width: 8.5rem; text-align: center;">
                <a href="/explore?categoryId=1454" class="m-3">
                    <i class="fas fa-child card-img-top fa-6x"></i>
                    <div class="card-body">
                        <p class="card-text">التربية</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col">
            <div class="card" style="width: 8.5rem; text-align: center;">
                <a href="/explore?categoryId=1438" class="m-3">
                    <i class="fas fa-ring card-img-top fa-6x"></i>
                    <div class="card-body">
                        <p class="card-text">الزواج</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<?php
include("v1/templates/foot.php"); ?>
<?php
include("v1/templates/head.php");
headerTemplate("تسجيل الدخول - فقه الحياة", true);
?>
<form action="/v1/php/login.php" method="post" class="login">
    <div class="container text-start">
        <div class="row mb-3 justify-content-center">
            <div class="col-sm-4">
                <label for="exampleInputEmail1" class="form-label">اسم المستخدم</label>
                <input name="username" type="username" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
        </div>
        <div class="row mb-3 justify-content-center">
            <div class="col-sm-4">
                <label for="exampleInputPassword1" class="form-label">كلمة السر</label>
                <input name="password" type="password" class="form-control col-4 " id="exampleInputPassword1">
            </div>
        </div>
        <div class="row mb-3 justify-content-center">
            <div class="col-sm-4">
                <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
            </div>
        </div>
    </div>
</form>
<?php
include("v1/templates/foot.php");
?>
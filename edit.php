<?php
include("v1/templates/head.php");
?>


<div class="inner-content">
    <textarea id="txtarea" spellcheck="false" placeholder="نص الحكمة الجديد..."></textarea>
    <div class="formDiv">
        <form action="https://farwaniyapharmacist.online/v1/php/editWisdom.php" method="post" id="editForm">
            <input type="submit" value="تأكيد" class="btn btn-primary btn-sm" id="submit" />
        </form>
        <form action="/home">
            <input type="submit" name="upvote" value="الرجوع إلى الصفحة الرئيسية" class="btn btn-danger btn-sm" id="back" />
        </form>

    </div>
    <div class="decoded"></div>
</div>
<div class="d-flex justify-content-center">
    <button id="showMoreBtn" class="btn bg-info mb-2">أظهر المزيد</button>
</div>

<?php
include("v1/templates/foot.php");
?>
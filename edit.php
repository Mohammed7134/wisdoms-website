<?php
include("v1/templates/head.php");
headerTemplate("تعديل الحكمة", true);
?>


<div class="inner-content">
    <textarea id="txtarea" spellcheck="false" placeholder="نص الحكمة الجديد..."></textarea>
    <div class="formDiv text-center">
        <form id="editForm">
            <input type="submit" value="تأكيد" class="btn btn-primary btn-sm" style="width:45%;font-size:24px;" id="submit" />
        </form>
        <form id="deleteForm">
            <input type="submit" value="مسح" class="btn btn-danger btn-sm" style="width:45%;font-size:24px;" id="deleteWisdom" />
        </form>
        <!-- <form action="/">
            <input type="submit" name="upvote" value="الرجوع إلى الصفحة الرئيسية" class="btn btn-danger btn-sm" id="back" />
        </form> -->

    </div>
</div>
<?php
include("/v1/templates/foot.php");
?>
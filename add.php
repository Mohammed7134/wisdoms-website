<?php
include("/v1/templates/head.php");
headerTemplate("إضافة حكمة", true);
?>


<div class="inner-content">
    <textarea id="txtarea" spellcheck="false" style="height:70vh" placeholder="نص الحكمة الجديد..."></textarea>
    <div class="formDiv">
        <form id="editForm">
            <input type="submit" value="تأكيد" class="btn btn-primary btn-sm w-100 p-6" style="font-size:24px;" id="submit" />
        </form>
    </div>
</div>
<?php
include("/v1/templates/foot.php");
?>
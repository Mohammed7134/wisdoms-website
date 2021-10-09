const author = "د.&nbsp;عبدالعزيز&nbsp;فيصل&nbsp;المطوع";
const authorWhats = "د. عبدالعزيز فيصل المطوع";

const eachBunch = 10;
let num = eachBunch;
let curSize;
let wisdomsArray = [];
let logStatus = false;

String.prototype.removeNBSP = function () {
    return this.replace(/&nbsp;/g, ' ').replace(/&#160;/g, ' ').replace(/\u00a0/g, ' ');
};

async function checkLogStatus() {
    const logStatusRes = await fetch("/v1/php/checkSession.php");
    const logStatusJson = await logStatusRes.json();
    logStatus = !logStatusJson.error;
}
const changeLogLabel = () => {
    if (logStatus) {
        document.querySelector(".login-link").style.display = "none";
        document.querySelector(".logout-button").style.display = "block";
    }
}

const loadCategoriesAxios = async () => {
    try {
        const res = await axios.get("/v1/php/getAllCategories.php?api=true");
        const categories = await res.data.categories;
        const list = document.querySelector("ul");
        for (let category of categories) {
            const li = document.createElement("li");
            const link = document.createElement("a");
            link.setAttribute("href", `/explore?categoryId=${category.id}`);
            link.classList.add("category-link");
            link.innerText = category.name;
            li.append(link);
            list.append(li);
        }
    } catch (e) {
        console.log("error: ", e)
    }

}

const createWisdom = (wisdom) => {
    let editLink = `<a class='edit-link me-3' href='edit/edit?wisdomId=${wisdom.id}'>تعديل</a>`;
    let shareLink = `<a class="share-link me-3" data-action="share/whatsapp/share" href="whatsapp://send?text=${encodeURI(wisdom.text.removeNBSP() + '\n\n' + authorWhats)}"><i class="fab fa-whatsapp fa-2x" style="color:green" aria-hidden="true"></i></a>`

    return `<blockquote class="quote-box">
        <p class="quotation-mark">“</p>
        <p class="quote-text wisdom-space">${wisdom.text.removeNBSP()}</p>
        <hr>
        <div class="blog-post-actions d-flex justify-content-between">
            <p class="d-inline" style="padding-right: 5%;">${author}</p>${logStatus ? editLink : shareLink}
        </div>
    </blockquote>`
}

const appendWisdom = async (i, edit) => {
    changeLogLabel();
    await $(".inner-content").append(createWisdom(wisdomsArray[i]));
    curSize = parseInt($('.quote-text').css('font-size'));
}

const displayWisdoms = async (edit) => {
    if (wisdomsArray.length > eachBunch) {
        for (let i = 0; i < eachBunch; i++) {
            await appendWisdom(i, edit);
        }
        document.getElementById("showMoreBtn").style.display = "block";
    } else {
        for (let i = 0; i < wisdomsArray.length; i++) {
            await appendWisdom(i, edit);
        }
    }
}

const setUpEditPage = async (params) => {
    const formElem = document.querySelector("#editForm");
    const textarea = document.querySelector("textarea");
    const messageArea = document.querySelector(".decoded");
    const wisdomId = params.get("wisdomId");
    const res = await axios.get(`/v1/php/getWisdomById.php?api=true&id=${wisdomId}`);
    let wisdom = await res.data.wisdom;
    textarea.innerText = wisdom.text;

    await $(".inner-content").prepend(createWisdom(wisdom));
    $(".inner-content").append(messageArea)
    document.querySelector(".edit-link").style.display = "none";

    const boxHeight = document.querySelector(".quote-box").offsetHeight;
    textarea.style.height = `${boxHeight * 0.6}px`;

    formElem.onsubmit = async (e) => {
        e.preventDefault();
        const newWisdom = textarea.value;
        data = {
            wisdom: newWisdom,
            wisdomId: wisdomId,
            oldWisdom: wisdom.text
        }
        if (wisdom.text !== newWisdom) {
            fetch('/v1/php/editWisdom.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            }).then(res => {
                return res.json();
            }).then(async data => {
                if (data.error === false) {
                    messageArea.innerHTML = "<p class='text-center mt-3'>تم التعديل بنجاح<p>";
                    const wisdomSpace = document.querySelector(".wisdom-space");
                    wisdomSpace.innerText = newWisdom;
                    wisdom.text = newWisdom
                } else {
                    messageArea.innerHTML = "<p class='text-center mt-3'>حدث خطأ<p>";
                }
            })
        } else {
            messageArea.innerHTML = "<p class='text-center mt-3'>لم يتغير شيء<p>";
        }
    };
}
const setUpSearchPage = async (params) => {
    const searchText = params.get('searchText');
    const res = await axios.get(`/v1/php/searchWisdom.php?searchText=${searchText}`);
    wisdomsArray = res.data.wisdoms;
    const counter = document.querySelector(".counter");
    counter.innerHTML += `كلمة البحث: ${searchText}`
    counter.innerHTML += "<br>"
    counter.innerHTML += `عدد النتائج: ${wisdomsArray.length}`
}
const setUpExploreCategoryPage = async (params) => {
    const catId = params.get('categoryId');
    const res = await axios.get(`/v1/php/exploreCategory.php?categoryId=${catId}`);
    wisdomsArray = res.data.wisdoms;
}

const loadWisdoms = async function () {
    await checkLogStatus();
    const params = new URLSearchParams(window.location.search);
    if (params.has('wisdomId')) {
        await setUpEditPage(params);
        displayWisdoms(true);
        changeLogLabel();
    } else {
        if (params.has('categoryId')) {
            await setUpExploreCategoryPage(params);
        } else if (params.has('searchText')) {
            await setUpSearchPage(params);
        } else {
            const res = await axios.get("/v1/php/getAllWisdoms.php?api=true");
            wisdomsArray = res.data.wisdoms;
        }
        displayWisdoms(false);
    }

}

function addfn() {
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
            $('#xmark').toggleClass('show');
            $('#content').toggleClass('inactive');
            $('body').toggleClass('scroll-disable');
        });
        $('#xmark').on('click', function () {
            $('#sidebar').toggleClass('active');
            $('#xmark').toggleClass('show');
            $('#content').toggleClass('inactive');
            $('body').toggleClass('scroll-disable');
        });
        // Increase/descrease font size
        $('.plus').bind("click", function () {
            curSize = parseInt($('.quote-text').css('font-size')) + 2;
            if (curSize <= 36)
                $('.quote-text').css('font-size', curSize);
        });

        $('.minus').bind("click", function () {
            curSize = parseInt($('.quote-text').css('font-size')) - 2;
            if (curSize >= 14)
                $('.quote-text').css('font-size', curSize);
        });
    });
}

async function showMore() {
    if ((num + eachBunch) < wisdomsArray.length) {
        num += eachBunch;
        for (let i = num - eachBunch; i < num; i++) {
            await appendWisdom(i, false);
        }
    } else if ((num + eachBunch) > wisdomsArray.length) {
        const lastLoop = wisdomsArray.length - (num + eachBunch);
        num += lastLoop;
        for (let i = num - lastLoop; i < wisdomsArray.length; i++) {
            await appendWisdom(i, false);
        }
        document.getElementById("showMoreBtn").style.display = "none";
    } else {
        document.getElementById("showMoreBtn").style.display = "none";
    }
    $('.quote-text').css('font-size', curSize);
}

const downloadData = async () => {
    await loadCategoriesAxios();
    await loadWisdoms();
    addfn();
    document.getElementById("showMoreBtn").addEventListener("click", showMore);
    document.getElementById("loading").style.display = "none";
}
const downloadLog = async () => {
    await loadCategoriesAxios();
    addfn();
}
if (document.URL.includes("edit")) {
    loadCategoriesAxios();
    loadWisdoms();
    addfn();
} else if (document.URL.includes("login")) {
    downloadLog();
} else {
    downloadData();
}

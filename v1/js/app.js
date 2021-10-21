const author = " "; //"د.&nbsp;عبدالعزيز&nbsp;فيصل&nbsp;المطوع";
const authorWhats = "د. عبدالعزيز فيصل المطوع";
const myStorage = window.sessionStorage;
const eachBunch = 10;
let num = eachBunch;
let curSize;
let wisdomsArray = [];
let logStatus = false;
let errorMessage = "";
var wisdomsIds = [];
let numberOfMessages = document.querySelector(".number-of-messages");
let allText = "";
const shareSquare = '<i class="fas fa-share-square far" style="font-size: 34px; vertical-align: Bottom;"></i>';


if (myStorage.getItem("wisdoms")) {
    wisdomsIds = JSON.parse(myStorage.getItem("wisdoms"));
}



const setNumVis = async () => {
    let shareButton = document.querySelector(".share-link");
    allText = "";
    if (wisdomsIds.length === 0) {
        numberOfMessages.style.display = "none";
        shareButton.removeAttribute("href");
    } else {
        shareButton.innerHTML = '<div class="spinner-border" role="status"></div>';
        for (let i = 0; i < wisdomsIds.length; i++) {
            const res = await axios.get(`/v1/php/getWisdomById.php?api=true&id=${wisdomsIds[i]}`);
            let wisdom = await res.data.wisdom;
            allText += wisdom.text;
            allText += '\n\n';
        }
        numberOfMessages.innerText = wisdomsIds.length;
        numberOfMessages.style.display = "flex";
        shareButton.innerHTML = '<i class="fab fa-whatsapp fa-2x" style="color:black" aria-hidden="true"></i>';
        shareButton.setAttribute("href", `whatsapp://send?text=${encodeURI(allText + '\n' + authorWhats)}`);
    }
}
setNumVis();



const timedFunc = async function () {
    wisdomsIds = [];
    myStorage.setItem("wisdoms", JSON.stringify(this.wisdomsIds));
    let addButtons = document.querySelectorAll(".add-button");

    for (let i = 0; i < addButtons.length; i++) {
        addButtons[i].innerHTML = shareSquare;
        addButtons[i].style.backgroundColor = "transparent";
    }
    numberOfMessages.style.display = "none";
    shareButton.removeAttribute("href");
    shareButton.style.transform = "scale(1.0)";
}

let shareButton = document.querySelector(".share-link");
shareButton.style["-webkit-user-select"] = "none";
numberOfMessages.style["-webkit-user-select"] = "none";
let pressTimer;

shareButton.addEventListener('touchstart', function () {
    shareButton.style.transform = "scale(1.5)";
    pressTimer = setTimeout(timedFunc, 1000);
    return false;
});
shareButton.addEventListener('touchend', function () {
    shareButton.style.transform = "scale(1.0)";
    clearTimeout(pressTimer);
    return false;
});


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
            if (!category.name.includes("جديدة")) {
                const li = document.createElement("li");
                const link = document.createElement("a");
                link.setAttribute("href", `/explore?categoryId=${category.id}`);
                link.classList.add("category-link");
                link.innerText = category.name;
                li.append(link);
                list.append(li);
            }
        }
    } catch (e) {
        console.log("error: ", e)
    }

}
const shareAction = async (wisdom, shareLink) => {
    shareLink.onclick = async () => {
        let index = wisdom.id;
        if (wisdomsIds.includes(index)) {
            for (var i = 0; i < wisdomsIds.length; i++) {
                if (wisdomsIds[i] === index) {
                    wisdomsIds.splice(i, 1);
                    shareLink.innerHTML = shareSquare;
                    shareLink.style.color = "black";
                }
            }
        } else {
            this.wisdomsIds.push(index);
            shareLink.innerHTML = shareSquare;
            shareLink.style.color = "gray";
        }
        myStorage.setItem("wisdoms", JSON.stringify(this.wisdomsIds));
        await setNumVis();
    }
}

const createWisdom = async (wisdom) => {
    let getPicLink = document.createElement("a");
    let shareLink = document.createElement("button");
    await shareAction(wisdom, shareLink);
    let editLink = document.createElement("a");
    editLink.classList.add("edit-link", "me-3");
    editLink.setAttribute("href", `edit/edit?wisdomId=${wisdom.id}`);
    editLink.innerText = "تعديل";
    // getPicLink.classList.add("btn", "btn-primary", "btn-sm");

    getPicLink.setAttribute("href", `test/test?wisdomId=${wisdom.id}&color=1`);
    getPicLink.innerHTML = '<i class="far fa-image" style="font-size: 40px; vertical-align: Bottom; color: Black;"></i>';

    shareLink.classList.add("add-button");
    if (wisdomsIds.includes(wisdom.id)) {
        shareLink.innerHTML = shareSquare;
        shareLink.style.color = "gray";
    } else {
        shareLink.innerHTML = shareSquare;
        shareLink.style.color = "black";
    }
    getPicLink.style.marginLeft = "15px";
    shareLink.style.marginLeft = "15px";
    editLink.style.marginLeft = "15px";
    getPicLink.style.marginBottom = "5px";
    shareLink.style.marginBottom = "5px";
    editLink.style.marginBottom = "5px";
    let quote = document.createElement("blockquote");
    quote.classList.add("quote-box");
    let mark = document.createElement("p");
    mark.innerText = "“";
    mark.classList.add("quotation-mark");
    quote.appendChild(mark);
    let wisSpace = document.createElement("p");
    wisSpace.classList.add("quote-text", "wisdom-space");
    wisSpace.innerText = wisdom.text.removeNBSP();
    quote.appendChild(wisSpace);
    quote.appendChild(document.createElement("hr"));
    let div = document.createElement("div");
    div.classList.add("blog-post-actions", "d-flex", "justify-content-between");
    let inDiv = document.createElement("div");
    inDiv.appendChild(shareLink);
    inDiv.appendChild(getPicLink);
    let par = document.createElement("p");
    par.classList.add("d-inline");
    par.style.paddingRight = "5%";
    par.innerHTML = author;
    div.appendChild(par);
    div.appendChild(logStatus ? editLink : inDiv);
    quote.appendChild(div);
    return quote;
}

const appendWisdom = async (i, edit) => {
    changeLogLabel();
    document.querySelector(".inner-content").append(await createWisdom(wisdomsArray[i]));
    curSize = parseInt($('.quote-text').css('font-size'));
}

const displayWisdoms = async (edit) => {
    document.querySelector(".font-size-controls").innerHTML = `<div class="btn-group" role="group" aria-label="font size control"><button type="button" class="btn btn-outline-primary plus">+</button><button type="button" class="btn btn-outline-primary minus">-</button></div>`

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

    document.querySelector(".inner-content").prepend(await createWisdom(wisdom));
    document.querySelector(".inner-content").append(messageArea);
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
    if (res.data.error) {
        counter.innerHTML += `<h4 style="color:red">${res.data.message}</h4>`
    } else {
        counter.innerHTML += `عدد النتائج: ${wisdomsArray.length}`
        displayWisdoms(false);
    }

}
const setUpExploreCategoryPage = async (params) => {
    const catId = params.get('categoryId');
    const res = await axios.get(`/v1/php/exploreCategory.php?categoryId=${catId}`);
    wisdomsArray = res.data.wisdoms;
    displayWisdoms(false);
    document.querySelector(".category-name").innerText = `${wisdomsArray[0].categories[0].name}`;
    document.querySelector(".category-number").innerText = "عدد الحكم: " + wisdomsArray.length;
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
            displayWisdoms(false);
        }
    }

}
function togglingActions() {
    $('#sidebar').toggleClass('active');
    $('#xmark').toggleClass('show');
    $('#content').toggleClass('inactive');
    $('body').toggleClass('scroll-disable');
}
function addfn() {
    $(document).ready(function () {
        $('#sidebarCollapse, #xmark').on('click', function (e) {
            e.stopPropagation();
            togglingActions();
        });
        $('#sidebar').on('click', function (e) {
            e.stopPropagation();
        });
        $('body,html').on('click', function () {
            if (document.querySelector("#xmark").classList.contains("show")) {
                togglingActions();
            }
        });
        // Increase/descrease font size
        $('.plus').bind("click", function () {
            curSize = parseInt($('.quote-text').css('font-size')) + 2;
            curSizeFar = parseInt($('.far').css('font-size')) + 2;
            if (curSize <= 36) {
                $('.quote-text').css('font-size', curSize);
                $('.far').css('font-size', curSizeFar);
            }
        });

        $('.minus').bind("click", function () {
            curSize = parseInt($('.quote-text').css('font-size')) - 2;
            curSizeFar = parseInt($('.far').css('font-size')) - 2;
            if (curSize >= 14) {
                $('.quote-text').css('font-size', curSize);
                $('.far').css('font-size', curSizeFar);
            }

        });
    });
}

async function showMore() {
    if (!document.URL.includes("edit") && document.URL.includes("about") && document.URL.includes("categories") && document.URL.includes("login")) {
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
    } else {
        const res = await axios.get("/v1/php/getAllWisdoms.php?api=true");
        wisdomsArray = res.data.wisdoms;
        displayWisdoms(false);
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
} else if (document.URL.includes("categories")) {
    downloadLog();
} else if (document.URL.includes("about")) {
    downloadLog();
} else {
    downloadData();
}

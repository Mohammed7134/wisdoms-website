const author = " "; //"د.&nbsp;عبدالعزيز&nbsp;فيصل&nbsp;المطوع";
const authorWhats = "د. عبدالعزيز فيصل المطوع";
const successText = "تم التعديل بنجاح";
const successDeleteText = "تم الحذف بنجاح";
const successAddText = "تمت الإضافة بنجاح"
const errorText = "حدث خطأ";
const noChangeText = "لم يتغير شيء";
const baseUrl = "/v1/php/"
const myStorage = window.sessionStorage;
const eachBunch = 10;
let currentWisdomIndex = eachBunch;
let wisdomsArray = [];
let logStatus = false;
let wisdomsIds = [];
let wisdomsText = [];
let numberOfMessages = document.querySelector(".number-of-messages");
let allText = "";
let scaleValue = 0;
let currentWisdomFontSize = 30;
let currentHashtagFontSize = 18;
let currentImageIconFontSize = 40;
let currentShareIconFontSize = 34;
let currentEditIconFontSize = 32;

//Delete wisdom 
const deleteWisdom = async (wisdom) => {
    const wisdomSpace = document.querySelector(".wisdom-space");
    data = {
        wisdomId: wisdom.id
    }
    fetch(baseUrl + 'deleteWisdom.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', },
        body: JSON.stringify(data),
    }).then(res => {
        return res.json();
    }).then(async data => {
        if (data.error === false) {
            showSnackbar(successDeleteText);
            wisdomSpace.innerText = "حُذِفَتْ";
            deleteElem.style.display = "none";
            textarea.style.display = "none";
            formElem.style.display = "none";
            document.querySelector(".hashtag").style.display = "none";
            document.querySelector(".get-pic-link").style.display = "none";
            document.querySelector(".add-button").style.display = "none";
            await updatingSharedWisdoms(wisdom, null);
        } else {
            await showSnackbar(errorText);
        }
    });
}
//Snackbar function
async function showSnackbar(message, wisdom = null, time = 2400) {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Write the message
    x.innerText = message;
    if (wisdom != null) {
        let deleteBtn = document.createElement("button");
        deleteBtn.classList.add("btn", "btn-danger");
        deleteBtn.addEventListener("click", async () => { await deleteWisdom(wisdom); });
        deleteBtn.innerText = "نعم";
        x.append(deleteBtn);
    }
    // Add the "show" class to DIV
    x.className = "show";

    // After defined seconds, remove the show class from DIV
    setTimeout(function () { x.className = x.className.replace("show", ""); }, time);
}

//Setting wisdom control icons
const makeIcon = (imageIcon, editIcon, shareIcon, color) => {
    if (myStorage.getItem("scaleFontSize")) {
        scaleValue = parseInt(myStorage.getItem("scaleFontSize"));
        document.querySelector("#customRange1").value = scaleValue;
    }
    let cont = document.createElement("div");
    let icon = document.createElement('i')
    if (imageIcon) {
        icon.classList.add("far", "fa-image", "wisdom-icon", "image-icon");
        icon.style.fontSize = (currentImageIconFontSize + scaleValue) + "px";
    } else if (shareIcon) {
        icon.classList.add("fas", "far", "wisdom-icon", "fa-share-square", "share-icon");
        icon.style.fontSize = (currentShareIconFontSize + scaleValue) + "px";
    } else if (editIcon) {
        icon.classList.add("far", "fas", "fa-edit", "wisdom-icon", "edit-link");
        icon.style.fontSize = (currentEditIconFontSize + scaleValue) + "px";
    };
    icon.style.verticalAlign = "Bottom"
    icon.style.color = color
    cont.appendChild(icon);
    return cont.innerHTML;
}
const shareButton = document.querySelector(".share-link");
const spinnerTag = '<wisdomFoote class="spinner-border" role="status"></wisdomFoote>';
const shareButtonTag = '<i class="fab fa-whatsapp fa-2x" style="color:black" aria-hidden="true"></i>';
const showMoreBtn = document.getElementById("showMoreBtn");
const formElem = document.querySelector("#editForm");
const deleteElem = document.querySelector("#deleteForm");
const textarea = document.querySelector("textarea");
const innerContent = document.querySelector(".inner-content");
const btnScrollToTopId = document.querySelector("#btnScrollToTopId");
if (myStorage.getItem("wisdoms")) {
    wisdomsIds = JSON.parse(myStorage.getItem("wisdoms"));
    if (wisdomsIds.length > 0) {
        shareButton.innerHTML = spinnerTag;
        let data = {
            wisdomsIds: wisdomsIds
        }
        fetch(baseUrl + 'getWisdomById.php?api=true', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', },
            body: JSON.stringify(data),
        }).then(res => {
            return res.json();
        }).then(async data => {
            if (data.error === false) {
                wisdomsText = data.wisdoms;
            } else {
                await showSnackbar(errorText);
            }
        }).then(() => {
            shareButton.innerHTML = shareButtonTag;
            numberOfMessages.innerText = wisdomsIds.length;
            numberOfMessages.style.display = "flex";
            setNumberVisibility();
        })
    }
}

shareButton.style["-webkit-user-select"] = "none";
numberOfMessages.style["-webkit-user-select"] = "none";

const setWisdomsStorage = () => {
    myStorage.setItem("wisdoms", JSON.stringify(wisdomsIds));
}
const setScaleFontSize = (value) => {
    myStorage.setItem("scaleFontSize", value);
}
//Scroll to top of the page function
window.onscroll = function () {
    if ($(window).scrollTop() > 2000) {
        btnScrollToTopId.style.display = "block";
        btnScrollToTopId.addEventListener("click", function () {
            window.scrollTo(0, 0);
        });
    } else {
        btnScrollToTopId.style.display = "none";
    }
}




//This is to remove the non-breaking-space from the wisdoms
String.prototype.removeNBSP = function () {
    return this.replace(/&nbsp;/g, ' ').replace(/&#160;/g, ' ').replace(/\u00a0/g, ' ');
};

//This function is to hide numberOfMessages and disable WhatsApp link
const disableWhatsAppIcon = () => {
    numberOfMessages.style.display = "none";
    shareButton.removeAttribute("href");
}

//This function set the WhatsApp Icon on the bottom right corner of the screen
//Everytime the page loads it is set to either zero or the number of wisdoms collected by the user
//It also prepares the text to be sent once the icon is tapped
const setNumberVisibility = async () => {
    console.log("function start");
    if (wisdomsIds.length === 0) {
        disableWhatsAppIcon();
    } else {
        shareButton.innerHTML = spinnerTag;
        numberOfMessages.innerText = wisdomsIds.length;
        numberOfMessages.style.display = "flex";
        shareButton.innerHTML = shareButtonTag;
        shareButton.setAttribute("href", `whatsapp://send?text=${encodeURI(wisdomsText.join("\n\n").removeNBSP() + '\n\n' + authorWhats)
            } `);
    }

}
setNumberVisibility();


//Making the wisdomsIds array empty and reflecting this in the interface;
const emptyingWisdomsIds = async function () {
    let addButtons = document.querySelectorAll(".add-button");
    wisdomsIds = [];
    wisdomsText = [];
    setWisdomsStorage();
    disableWhatsAppIcon();
    shareButton.style.transform = "scale(1.0)";
    for (let i = 0; i < addButtons.length; i++) {
        addButtons[i].innerHTML = makeIcon(false, false, true, "black");
    }
}

//This function is to call emptyingWisdomsIds array by the user by touching the WhatsApp icon for 1 sec
let pressTimer;
shareButton.addEventListener('touchstart', function () {
    shareButton.style.transform = "scale(1.5)";
    pressTimer = setTimeout(emptyingWisdomsIds, 1000);
    return false;
});
shareButton.addEventListener('touchend', function () {
    shareButton.style.transform = "scale(1.0)";
    clearTimeout(pressTimer);
    return false;
});

//This function is to check whether the user login status is active or not when going from one page to another
async function checkLogStatus() {
    const logStatusRes = await fetch(baseUrl + "checkSession.php");
    const logStatusJson = await logStatusRes.json();
    logStatus = !logStatusJson.error;
}

// This function is to display logout link instead of login in case the user is logged in
const changeLogLabel = async () => {
    await checkLogStatus();
    if (logStatus) {
        document.querySelector(".login-link").style.display = "none";
        document.querySelector(".logout-button").style.display = "block";
        document.querySelector(".new-wisdom-button").style.display = "block";
    }
}

//This function is to load categories and display them in the side menue
const loadCategoriesAxios = async () => {
    try {
        const res = await axios.get(baseUrl + "getAllCategories.php?api=true");
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
const updatingSharedWisdoms = async (wisdom, shareLink) => {
    if (wisdomsIds.includes(parseInt(wisdom.id))) {
        for (var i = 0; i < wisdomsIds.length; i++) {
            if (wisdomsIds[i] === parseInt(wisdom.id)) {
                wisdomsIds.splice(i, 1);
                wisdomsText.splice(i, 1);
                if (shareLink) {
                    shareLink.innerHTML = makeIcon(false, false, true, "black");
                }
            }
        }
    } else {
        if (shareLink) {
            wisdomsIds.push(parseInt(wisdom.id));
            wisdomsText.push(wisdom.text);
            if (shareLink) {
                shareLink.innerHTML = makeIcon(false, false, true, "gray");
            }
        }
    }
    setWisdomsStorage();
    await setNumberVisibility();
}
//This function is adding onclick function to share icon on each wisdom
//When user clicks the icon it looks for the wisdom id in the wisdomsIds array
//If found, will be removed otherwise added with the corresponding changes in interface
const addOrRemoveWisdomId = async (wisdom, shareLink) => {
    shareLink.onclick = async () => {
        updatingSharedWisdoms(wisdom, shareLink);
    }
}
//This function is for creating wisdoms template
const createWisdom = async (wisdom) => {


    let getPicLink = document.createElement("a");
    getPicLink.setAttribute("href", `/photo.html?wisdomId=${wisdom.id}&color=1`);
    getPicLink.classList.add("get-pic-link");
    getPicLink.innerHTML = makeIcon(true, false, false, "black");

    let editLink = document.createElement("a");
    editLink.setAttribute("href", `/edit/edit?wisdomId=${wisdom.id}`);
    editLink.innerHTML = makeIcon(false, true, false, "black");

    const link = document.createElement("a");
    const category = wisdom.categories[0];
    link.setAttribute("href", `/explore?categoryId=${category.id}`);
    link.style.paddingRight = "20px"
    link.style.fontSize = (currentHashtagFontSize + scaleValue) + "px";

    link.classList.add("hashtag");
    const params = new URLSearchParams(window.location.search);
    if (!params.has("categoryId")) {
        link.innerText = category.name;
    }
    let shareLink = document.createElement("button");
    shareLink.classList.add("add-button");
    // shareLink.innerHTML = makeIcon(false, false, true, "black");
    if (wisdomsIds.includes(wisdom.id)) {
        shareLink.innerHTML = makeIcon(false, false, true, "gray");
    } else {
        shareLink.innerHTML = makeIcon(false, false, true, "black")
    }
    // if (logStatus) {
    await addOrRemoveWisdomId(wisdom, shareLink);
    // }
    let quote = document.createElement("blockquote");
    quote.classList.add("quote-box");

    let mark = document.createElement("p");
    mark.innerText = "“";
    mark.classList.add("quotation-mark");
    quote.appendChild(mark);

    let wisSpace = document.createElement("p");
    wisSpace.classList.add("quote-text", "wisdom-space");
    wisSpace.style.fontSize = (currentWisdomFontSize + scaleValue) + "px";
    wisSpace.innerText = wisdom.text.removeNBSP();
    quote.appendChild(wisSpace);

    quote.appendChild(document.createElement("hr"));

    let wisdomFooter = document.createElement("div");
    wisdomFooter.classList.add("blog-post-actions", "d-flex", "justify-content-between");

    let wisdomFooterIcons = document.createElement("div");
    wisdomFooterIcons.style.display = "flex"
    wisdomFooterIcons.style.alignItems = "flex-start"
    const defaultSpace = document.createElement("p");
    wisdomFooterIcons.appendChild(logStatus ? editLink : defaultSpace);
    wisdomFooterIcons.appendChild(shareLink);
    wisdomFooterIcons.appendChild(getPicLink);
    wisdomFooter.appendChild(wisdomFooterIcons);
    wisdomFooter.prepend(link);
    quote.appendChild(wisdomFooter);

    return quote;
}

//This function is to assign the wisdom template to the inner-content tag
const appendWisdom = async (i) => {
    document.querySelector(".inner-content").append(await createWisdom(wisdomsArray[i]));
}

//This function checks whether the number of loading wisdoms is more than predefined bunch number or not;
const displayWisdoms = async () => {
    document.querySelector(".font-size-controls").style.display = "block"
    const showMoreButtonDisplay = wisdomsArray.length > eachBunch;
    const chosenLength = showMoreButtonDisplay ? eachBunch : wisdomsArray.length;
    for (let i = 0; i < chosenLength; i++) {
        await appendWisdom(i);
    }
    showMoreBtn.style.display = showMoreButtonDisplay ? "block" : "none";
}

//This function is to setup the edit page
//It hides the edit link, place the selected wisdom in wisdome template and handles the submition of th edit result
const setUpEditPage = async (params) => {
    const wisdomId = params.get("wisdomId");
    if (wisdomId) {
        const res = await axios.get(`${baseUrl}getWisdomById.php?api=true&id=${wisdomId}`);
        let wisdom = await res.data.wisdom;
        if (wisdom) {
            textarea.innerHTML = wisdom.text;
            innerContent.prepend(await createWisdom(wisdom));
            formElem.onsubmit = async (e) => {
                e.preventDefault();
                const newWisdom = textarea.value;
                await showSnackbar("يرجى الإنتظار");
                data = {
                    wisdom: newWisdom,
                    wisdomId: wisdomId,
                    oldWisdom: wisdom.text
                }
                if (wisdom.text !== newWisdom) {
                    fetch(baseUrl + 'editWisdom.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', },
                        body: JSON.stringify(data),
                    }).then(res => {
                        return res.json();
                    }).then(async data => {
                        if (data.error === false) {
                            await showSnackbar(successText);
                            wisdomSpace.innerText = newWisdom.removeNBSP();
                            wisdom.text = newWisdom.removeNBSP()
                        } else {
                            await showSnackbar(errorText);
                        }
                    })
                } else {
                    await showSnackbar(noChangeText);
                }
            };
            deleteElem.onsubmit = async (e) => {
                e.preventDefault();
                await showSnackbar("هل أنت متأكد من إزالة الحكمة؟ ", wisdom);
            };
            const boxHeight = document.querySelector(".quote-box").offsetHeight;
            const wisdomSpace = document.querySelector(".wisdom-space");
            textarea.style.height = `${boxHeight * 0.6}px`;
            document.querySelector(".edit-link").style.display = "none";
        } else {
            window.location.href = `/`;
        }
    } else if (params.get("newWisdom") === "true") {
        formElem.onsubmit = async (e) => {
            e.preventDefault();
            formElem.elements[0].disabled = true;
            const newWisdom = textarea.value;
            await showSnackbar("يرجى الإنتظار");
            data = {
                wisdom: newWisdom,
                categories: ["جديدة"]
            }
            if (newWisdom != "") {
                fetch(baseUrl + 'createWisdom.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', },
                    body: JSON.stringify(data),
                }).then(res => {
                    return res.json();
                }).then(async data => {
                    if (data.error === false) {
                        await showSnackbar(successAddText);
                        window.location.href = `/edit/edit?wisdomId=${data.wisdom.id}`;
                    } else {
                        formElem.elements[0].disabled = false;
                        await showSnackbar(errorText);
                    }
                })
            } else {
                await showSnackbar(noChangeText);
            }
        }

    }

}

//This function loads the wisdoms resulted from the search and display the results
const setUpSearchPage = async (params) => {
    const searchText = params.get('searchText');
    const res = await axios.get(`${baseUrl}searchWisdom.php?searchText=${searchText}`);
    const counter = document.querySelector(".counter");
    wisdomsArray = res.data.wisdoms;
    counter.innerHTML += `كلمة البحث: ${searchText} ` + "<br>";
    if (res.data.error)
        counter.innerHTML += `<h5 style="color:red">${res.data.message}</h5>`
    else {
        counter.innerHTML += `عدد النتائج: ${wisdomsArray.length}`
        displayWisdoms();
    }
}

//This function loads the wisdoms resulted from the the chosen category and display the results
const setUpExploreCategoryPage = async (params) => {
    const catId = params.get('categoryId');
    const res = await axios.get(`${baseUrl}exploreCategory.php?categoryId=${catId}`);
    wisdomsArray = res.data.wisdoms;
    displayWisdoms();
    document.querySelector(".category-name").innerText = `${wisdomsArray[0].categories[0].name} `;
    document.querySelector(".category-number").innerText = "عدد الحكم: " + wisdomsArray.length + (wisdomsArray.length == 1000 ? "+" : "");
}

//This function is to call the corresponding functions with regard to what page the user in;
const loadWisdoms = async function () {
    changeLogLabel();
    const params = new URLSearchParams(window.location.search);
    if (params.has('wisdomId') || params.has("newWisdom"))
        await setUpEditPage(params);
    else if (params.has('categoryId'))
        await setUpExploreCategoryPage(params);
    else if (params.has('searchText'))
        await setUpSearchPage(params);
    else {
        const res = await axios.get(baseUrl + "getAllWisdoms.php?api=true");
        wisdomsArray = res.data.wisdoms;
        displayWisdoms();
    }
}

//Setting up sidebar functionality
function togglingActions() {
    $('#sidebar').toggleClass('active');
    $('#xmark').toggleClass('show');
    $('#content').toggleClass('inactive');
    $('body').toggleClass('scroll-disable');
}



//Applying sidebar and font size functionalities
function sidebarFontControlFunctionality() {
    document.querySelector("#customRange1").oninput = function () {
        const newWisdomSize = currentWisdomFontSize + parseInt(this.value);
        const newHashtagSize = currentHashtagFontSize + parseInt(this.value);
        const newImageIconFontSize = currentImageIconFontSize + parseInt(this.value);
        const newShareIconFontSize = currentShareIconFontSize + parseInt(this.value);
        const newEditIconFontSize = currentEditIconFontSize + parseInt(this.value);
        $(".hashtag").css('font-size', newHashtagSize + "px");
        $('.quote-text').css('font-size', newWisdomSize + "px");
        $('.image-icon').css('font-size', newImageIconFontSize + "px");
        $('.share-icon').css('font-size', newShareIconFontSize + "px");
        $('.edit-link').css('font-size', newEditIconFontSize + "px");

        setScaleFontSize(this.value);
    }


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
    });
    const noWisdoms = "لا يوجد حكم بالسلة";
    const longPressRequired = "اضغط مطولا لمسح الحكم بالسلة";
    shareButton.addEventListener('click', () => showSnackbar(wisdomsText.length > 0 ? longPressRequired : noWisdoms));
    if (wisdomsText.length > 0) {
        shareButton.onclick = whatsAppShared;
    }
}

//This function shows the spinner and hides the show more button
const loadingWisdomsSign = () => {
    showMoreBtn.style.display = "none";
    document.querySelector(".spinner-border-load-more").style.display = "block";
}
//This function shows the show more button and hides the spinner
const wisdomsLoadedSign = () => {
    showMoreBtn.style.display = "block";
    document.querySelector(".spinner-border-load-more").style.display = "none";
}

//Setting up show more button functionality
async function showMore() {
    const maxNumber = currentWisdomIndex + eachBunch
    if (maxNumber < wisdomsArray.length) {
        currentWisdomIndex += eachBunch;
        for (let i = currentWisdomIndex - eachBunch; i < currentWisdomIndex; i++) {
            await appendWisdom(i);
        }
    } else {
        for (let i = currentWisdomIndex; i < wisdomsArray.length; i++) {
            await appendWisdom(i);
        }
        showMoreBtn.style.display = "none";
    }
}

//This function loads more wisdoms from the server
const showMoreNew = async () => {
    loadingWisdomsSign();
    const res = await axios.get(baseUrl + "getAllWisdoms.php?api=true");
    wisdomsArray = res.data.wisdoms;
    displayWisdoms();
    wisdomsLoadedSign();
}

const downloadCategoriesAndWisdoms = async (indexPage) => {
    await loadCategoriesAxios();
    await loadWisdoms();
    sidebarFontControlFunctionality();
    showMoreBtn.addEventListener("click", indexPage ? showMoreNew : showMore);
    document.getElementById("loading").style.display = "none";
}

const downloadCategoriesOnly = async () => {
    await loadCategoriesAxios();
    sidebarFontControlFunctionality();
}

if (document.URL.includes("edit") || document.URL.includes("add")) {
    loadCategoriesAxios();
    loadWisdoms();
    sidebarFontControlFunctionality();
} else if (document.URL.includes("login")) {
    downloadCategoriesOnly();
} else if (document.URL.includes("about")) {
    downloadCategoriesOnly();
} else if (document.URL.includes("edit") || document.URL.includes("about") || document.URL.includes("explore") || document.URL.includes("searchText")) {
    downloadCategoriesAndWisdoms(false);
} else {
    downloadCategoriesAndWisdoms(true);
}

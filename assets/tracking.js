function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getQueryParam(param) {
    let urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

let utm_source = getQueryParam("utm_source") || getCookie("utm_source") || "organic";
if (getQueryParam("utm_source")) {
    setCookie("utm_source", utm_source, 30);
}

let utm_campaign = getQueryParam("utm_campaign") || getQueryParam("sub_id_2") || getCookie("utm_campaign") || '';
if (getQueryParam("utm_campaign") || getQueryParam("sub_id_2")) {
    setCookie("utm_campaign", utm_campaign, 30);
}

let utm_medium = getQueryParam("utm_medium") || getQueryParam("sub_id_3") || getCookie("utm_medium") || '';
if (getQueryParam("utm_medium") || getQueryParam("sub_id_3")) {
    setCookie("utm_medium", utm_medium, 30);
}

let utm_content = getQueryParam("utm_content") || getQueryParam("sub_id_4") || getCookie("utm_content") || '';
if (getQueryParam("utm_content") || getQueryParam("sub_id_4")) {
    setCookie("utm_content", utm_content, 30);
}

let sub10 = 'u' + getQueryParam("sub_id_10") ? '&sub_id_10=' + getQueryParam("sub_id_10") : (getCookie("sub_id_10") ? '&sub_id_10=' + getCookie("sub_id_10") : '');
if (getQueryParam("sub_id_10")) {
    setCookie("sub_id_10", getQueryParam("sub_id_10"), 30);
}

let source = window.location.hostname;
let utm_term = "rdrdirect";

let tail = `utm_source=${utm_source}&source=${source}&utm_campaign=${utm_campaign}&utm_medium=${utm_medium}&utm_content=${utm_content}&utm_term=${utm_term}${sub10}`;
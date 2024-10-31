window.addEventListener("load", function (event) {
    var getCookies = function () {
        var pairs = document.cookie.split(";");
        var cookies = {};
        for (var i = 0; i < pairs.length; i++) {
            var pair = pairs[i].split("=");
            cookies[(pair[0] + "").trim()] = unescape(pair.slice(1).join("="));
        }
        return cookies;
    };

    let allCookies = getCookies();
    console.log('Cookies:', allCookies); // Для отладки

    let fbp = allCookies._fbp || "no_fbp_cookie";
    let fbc = allCookies._fbc || "no_fbc_cookie";

    console.log('FBP:', fbp); // Для отладки
    console.log('FBC:', fbc); // Для отладки

    let param1Field = document.getElementById("param1");
    let param2Field = document.getElementById("param2");

    if (param1Field) param1Field.value = fbp;
    if (param2Field) param2Field.value = fbc;

    // Для отладки
    if (param1Field) console.log('Param1 value set:', param1Field.value);
    if (param2Field) console.log('Param2 value set:', param2Field.value);
});

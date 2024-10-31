//fname
$('#fname').on('blur', function () {
    validatefname();
});

function validatefname() {
    var fname = $('#fname').val();
    var errorSpan = $('#fname_error');
    var specialCharRegex = /[!@#$%^&*(),.?"`':{}|<>0-9]/; // Проверка на спецсимволы и цифры

    // Сброс сообщений об ошибке и стилей
    errorSpan.text('');
    $('#fname').removeClass('error success');

    if (fname.length < 2) {
        errorSpan.text("Ім'я занадто коротке");
        $('#fname').addClass('error');
    } else if (fname.length > 32) {
        errorSpan.text("Ім'я занадто довге");
        $('#fname').addClass('error');
    } else if (specialCharRegex.test(fname)) {
        var invalidChar = fname.match(specialCharRegex)[0];
        errorSpan.text('Неприпустимий символ: ' + invalidChar);
        $('#fname').addClass('error');
        $('#fname').addClass('error');
    } else {
        $('#fname').addClass('success');
    }
}


//lname
$('#lname').on('blur', function () {
    validatelname();
});

function validatelname() {
    var lname = $('#lname').val();
    var errorSpan = $('#lname_error');
    var specialCharRegex = /[!@#$%^&*(),.?"`':{}|<>0-9]/; // Проверка на спецсимволы и цифры

    // Сброс сообщений об ошибке и стилей
    errorSpan.text('');
    $('#lname').removeClass('error success');

    if (lname.length < 2) {
        errorSpan.text("Прізвище занадто коротке");
        $('#lname').addClass('error');
    } else if (lname.length > 32) {
        errorSpan.text("Прізвище занадто довге");
        $('#lname').addClass('error');
    } else if (specialCharRegex.test(lname)) {
        var invalidChar = lname.match(specialCharRegex)[0];
        errorSpan.text('Неприпустимий символ: ' + invalidChar);
        $('#lname').addClass('error');
    } else {
        $('#lname').addClass('success');
    }
}


$('#phone').inputmask({
    mask: "+38(099) 999-99-99",
    placeholder: "_",
    showMaskOnHover: false,
    showMaskOnFocus: true
});

$('#phone').on('blur', function () {
    validatePhoneNumber();
});

function validatePhoneNumber() {
    var phoneNumber = $('#phone').val();
    var errorSpan = $('#phone_error');
    var phoneMask = /^\+38\(0\d{2}\) \d{3}-\d{2}-\d{2}$/;


    // Сброс сообщений об ошибке и стилей
    errorSpan.text('');
    $('#phone').removeClass('error success');

    if (phoneMask.test(phoneNumber)) {
        $('#phone').addClass('success');
    } else {
        errorSpan.text('Будь ласка, заповніть номер до кінця');
        $('#phone').addClass('error');
    }
}

$('#email').on('blur', function () {
    validateEmail();
});

function validateEmail() {
    var email = $('#email').val();
    var errorSpan = $('#email_error');
    var emailMask = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Простая регулярка для проверки email

    // Сброс сообщений об ошибке и стилей
    errorSpan.text('');
    $('#email').removeClass('error success');

    if (emailMask.test(email)) {
        $('#email').addClass('success');
    } else {
        errorSpan.text('Будь ласка, введіть коректний email');
        $('#email').addClass('error');
    }
}

function validateForm() {
    var isValid = true;

    // Валидация каждого поля
    validatefname();
    if ($('#fname').hasClass('error')) isValid = false;

    validatelname();
    if ($('#lname').hasClass('error')) isValid = false;

    validateEmail();
    if ($('#email').hasClass('error')) isValid = false;

    validatePhoneNumber();
    if ($('#phone').hasClass('error')) isValid = false;

    // Валидация чекбокса
    if (!$('#checkbox').is(':checked')) {
        $('#checkbox_error').text('Необхідна згода');
        $('.form__check').addClass('errorCheck');
        isValid = false;
    } else {
        $('#checkbox_error').text('');
        $('.form__check').removeClass('errorCheck');
    }

    return isValid;
}

$('#checkbox').on('change', function () {
    if ($(this).is(':checked')) {
        $('.form__check').removeClass('errorCheck');
        $('#checkbox_error').empty();
    }
});

function checkUserAgent() {
    var ua = navigator.userAgent;

    // Проверка на iOS
    if (ua.includes('iPhone') || ua.includes('iPad')) {
        return true;
    }

    // Проверка на Android версии выше 9
    if (ua.includes('Android')) {
        var match = ua.match(/Android (\d+(\.\d+)?)/);
        var version = match ? parseFloat(match[1]) : null;

        if (version && version > 9) {
            return true;
        }
    }

    return false;
}

// Вызов функции и установка значения переменной
var isGoodDevice = checkUserAgent();


$('#form').on('submit', function (event) {
    event.preventDefault(); // Отмена стандартной отправки формы

    const tail = $("input[name='tail']").val(); // Получаем значение tail перед отправкой

    if (validateForm()) {
        $(".form__wrapper").hide();
        $(".loadingio-spinner-dual-ring-jyzwg8ry7rk").show();
        $("#wait_id").show();

        sendAjaxForm('result_form', 'form', '/endpoint.php', tail); // Передаем tail в sendAjaxForm

        function sendAjaxForm(result_form, mainform, url, tail) {
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: $("#" + mainform).serialize(),
                success: function (response) {
                    if (response.message === "new_lead" || response.message === "rewrite_lead") {
                        console.log(response.message); // Выводим сообщение
                        if (isGoodDevice) {
                            console.log("flash!"); // Выводим сообщение
                            if (typeof gtag === "function") {
                                gtag('event', 'form_send');
                            }
                        }
                    }
                    rdr(response.id, tail); // Передаем id и tail в rdr

                    console.log(response);
                },
                error: function (response) {
                    console.log("error!");
                }
            });
        }
    }
});

function rdr(id, tail) { // Добавляем параметр tail
    const d = new Date();
    let exdays = 4; // 4 дня
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let ExpiresDate = d.toUTCString();
    document.cookie = "SendForm=1; expires=" + ExpiresDate + "; path=/";

    // Используем tail для генерации redirectURL
    const redirectURL = 'https://creditop.com.ua/?' + tail + '&sub_id_10=u' + id;
    window.open(redirectURL, '_blank');

    // Сохраняем id в cookie
    document.cookie = "leadId=" + id + "; path=/";

    setTimeout(() => {
        window.location.reload();
    }, 3000); // Обновление страницы через 3 секунды
}

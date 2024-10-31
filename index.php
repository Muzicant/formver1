<?php
if ( session_status() === PHP_SESSION_NONE ) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Генерация токена
}
?>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form ver 1</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>

<?php require_once "vars.php" ?>
<?php require_once "tracking.php" ?>
<form action="#" class="form__container" id="form" >
    <div class="form__input">
        <label for="fname"><?= $tr_fname; ?></label>
        <input class="input__field _req" id="fname" type="text" name="fname" placeholder="<?= $tr_fname_placeholder; ?>">
        <div class="icon"></div>
        <span id="fname_error" class="error"></span>
    </div>

    <div class="form__input">
        <label for="lname"><?= $tr_lname;?></label>
        <input class="input__field _req" id="lname" type="text" name="lname" placeholder="<?= $tr_lname_placeholder;?>">
        <div class="icon"></div>
        <span id="lname_error" class="error"></span>
    </div>

    <div class="form__input">
        <label for="phone"><?= $tr_phone; ?></label>
        <input class="input__field _req" id="phone" type="tel" name="phone" placeholder="<?= $tr_phone_placeholder; ?>">
        <div class="icon"></div>
        <span id="phone_error" class="error"></span>
    </div>
    <div class="form__input">
        <label for="email"><?= $tr_email;?></label>
        <input class="input__field _req _email" id="email" type="email" name="email" placeholder="<?= $tr_email_placeholder; ?>">
        <div class="icon"></div>
        <span id="email_error" class="error"></span>
    </div>

    <div class="form__check">
        <input type="checkbox" id="checkbox"  class="_req">

        <label for="checkbox">
            <div><span><?= $tr_accept_my_data; ?> <a href="/policy" target="_blank"><?= $tr_accept_my_data2_link; ?></a></span></div>
        </label>
        <span id="checkbox_error" class="error"></span>
    </div>
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="hidden" value="<?= $utm_source; ?>" id="utm_source" name="utm_source">
    <input type="hidden" value="<?= $utm_campaign; ?>" id="utm_campaign" name="utm_campaign">
    <input type="hidden" value="<?= $utm_medium; ?>" id="utm_source" name="utm_medium">
    <input type="hidden" value="<?= $utm_content; ?>" id="utm_content" name="utm_content">
    <input type="hidden" name="get_ip" value="<?= $userIP; ?>">
    <input type="hidden" name="get_ua" value="<?= $userAgent; ?>">
    <input type="hidden" name="tail" value="<?= $tail; ?>">
    <input type="hidden" value="" id="param1" name="param1">
    <input type="hidden" value="" id="param2" name="param2">
    <button class="form__btn button-style" id="form-btn" type="submit"><?= $tr_send_button;?></button>
</form>
<footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/inputmask.min.js" integrity="sha512-eD+19OyeG3GbJ6QGk9uI7TfTozYXAVPz6/Va/YVVuBz7ZFvAeiFzol0whJplf9l6cNQcA8sVxVXvCFW489cAVA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.js" integrity="sha512-S88Hc9bQbJvnw02g2Ge4NtLTUPpzMApjrIvTs8/kexCIDiqWBx2QJFogQ5VaUKfO5nIVvuDGhjTsUIQE4Lm3bA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/assets/ga.js"> </script>
    <script src="/assets/script.js"> </script>
</footer>
</body>
</html>
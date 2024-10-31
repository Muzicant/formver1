window.addEventListener("load", function (event) {

    function getGaData() {
        // Извлечение Client ID из куки
        function getClientIdFromCookie() {
            let gaCookie = document.cookie.split('; ').find(row => row.startsWith('_ga='));
            if (gaCookie) {
                let parts = gaCookie.split('=')[1].split('.');
                if (parts.length >= 4) {
                    return parts[2] + "." + parts[3];
                }
            }
            return null;
        }

        return {
            clientId: getClientIdFromCookie(),
        };
    }

    let gaData = getGaData();
    console.log("Client ID:", gaData.clientId);

    let clientId =  gaData.clientId || "no_data";

    let param1Field = document.getElementById("param1");

    if (param1Field) param1Field.value = clientId;

    // Для отладки
    if (param1Field) console.log('Param1 value set:', param1Field.value);
});

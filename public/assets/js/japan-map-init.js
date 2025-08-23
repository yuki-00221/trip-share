(function () {
    var visitedPrefs = window.visitedPrefs || [];
    var allPrefs = Array.from({ length: 47 }, (_, i) => i + 1);

    var areas = allPrefs.map(function (code) {
        return {
            code: code,
            color: visitedPrefs.includes(code) ? "#4CAF50" : "#CCCCCC",
            hoverColor: visitedPrefs.includes(code) ? "#66BB6A" : "#999999"
        };
    });

    var d = new jpmap.japanMap(document.getElementById("japan-map"), {
        showsPrefectureName: false,
        width: document.getElementById("japan-map").offsetWidth,
        movesIslands: true,
        lang: 'ja',
        areas: areas,
        onSelect: function (data) {
            window.location.href = 'prefecture.php?pref=' + data.code;
        }
    });
})();

$(".add-friend").on("click", function (e) {
    e.preventDefault();
    urlfriend = friend;
    username = e.target.dataset["username"];
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        method: "POST",
        url: urlfriend,
        data: { username: username },
    }).done(function (data) {
        location.reload();
        if (data) {
            alert("permintaan telah dikirim.");
        } else {
            alert("permintaan telah dibatalkan.");
        }
    });
});

$(".accept").on("click", function (e) {
    e.preventDefault();
    username = e.target.dataset["username"];
    urlacc = acc;
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        method: "POST",
        url: urlacc,
        data: { username: username },
    }).done(function () {
        location.reload();
    });
});

$(".reject").on("click", function (e) {
    e.preventDefault();
    username = e.target.dataset["username"];
    urlrjc = rjc;
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        method: "POST",
        url: urlrjc,
        data: { username: username },
    }).done(function () {
        location.reload();
    });
});

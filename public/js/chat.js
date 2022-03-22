$(function () {
    function getMessage() {
        urlget = get;
        username = username;
        id = idFrom;
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            method: "POST",
            url: urlget,
            data: { username: username },
        }).done(function (response) {
            chatBox = $(".chat-box");
            chatBox.empty();
            response.forEach((message) => {
                var div = '';
                if (message.from == id) {
                    justify = 'justify-content-end';
                    bg = 'bg-success';
                    orderM = 'order-2';
                    orderS = 'order-1';
                } else {
                    justify = 'justify-content-start';
                    bg = 'bg-secondary';
                    orderM = 'order-1';
                    orderS = 'order-2';
                }
                var date = new Date(message.date);
                if ((date.getMonth() + 1) < 10) {
                    month = '0' + (date.getMonth() + 1);
                } else {
                    month = (date.getMonth() + 1);
                }
                div += '<div class="d-flex '+justify+' align-items-baseline mt-1">';
                div += '<div class="px-2 rounded '+orderM+' '+bg+' text-white fs-5">';
                div += message.message;
                div += '</div>';
                div += '<small class="mx-1 text-underline '+orderS+'" style="font-size: 0.7rem;">'+date.getHours()+':'+date.getMinutes()+' | '+date.getDate()+'.'+month+'</small>';
                div += '</div>';
                chatBox.prepend(div);
            });
            scrollDown();
        });
    }

    function scrollDown() {
        chatBox = document.querySelector(".chat-box");
        $(".chat-box").scrollTop(chatBox.scrollHeight);
    }

    getMessage();

    setInterval(function () {
        getMessage();
    }, 10000);
    $(".message").on("submit", function (e) {
        e.preventDefault();
        urlsend = send;
        message = e.target.firstElementChild.firstElementChild;
        username = username;
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            method: "POST",
            url: urlsend,
            data: { username: username, message: message.value },
        }).done(function (response) {
            message.value = "";
            getMessage();
        });
    });
});


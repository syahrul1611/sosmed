$(".like").on("click", function (e) {
    e.preventDefault();
    urllike = like;
    slug = e.target.dataset["slug"];
    likeElement = e.target;
    likeCountElement = e.target.parentElement.previousElementSibling;
    console.log(slug);
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        method: "POST",
        url: urllike,
        data: { slug: slug },
    }).done(function (response) {
        console.log(response);
        likeCountElement.innerHTML = response.count + " Suka";
        if (response.like) {
            likeElement.style.color = "red";
        } else {
            likeElement.style.color = "black";
        }
    });
});

$(".comment").on("submit", function (e) {
    e.preventDefault();
    urlcomment = comment;
    slug = e.target.dataset["slug"];
    cmt = e.target.firstElementChild.firstElementChild;
    commentBox = e.target.nextElementSibling;
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        method: "POST",
        url: urlcomment,
        data: { slug: slug, comment: cmt.value },
    }).done(function (response) {
        var li = document.createElement("li");
        li.className = "mb-3 border-bottom";
        var img = document.createElement("img");
        img.className = "rounded-circle";
        img.setAttribute("src", "/storage/" + response.image);
        img.setAttribute("width", "36");
        img.style.aspectRatio = "1/1";
        var span = document.createElement("span");
        var spantext = document.createTextNode(" " + response.name);
        span.appendChild(spantext);
        var a = document.createElement("a");
        a.className = "link-dark text-decoration-none";
        a.setAttribute("href", "/dashboard-" + response.username);
        var p = document.createElement("p");
        p.className = "position-relative";
        var ptext = document.createTextNode(response.comment);
        p.appendChild(ptext);
        var time = document.createElement("span");
        time.className = "position-absolute end-0 pe-1 comment-style";
        var timetext = document.createTextNode(response.time);
        time.appendChild(timetext);
        p.appendChild(time);
        a.appendChild(img);
        a.appendChild(span);
        li.appendChild(a);
        li.appendChild(p);
        commentBox.prepend(li);
        cmt.value = "";
    });
});

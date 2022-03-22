const image = document.querySelectorAll(".image");
const oFReader = new FileReader();
for (let i = 0; i < image.length; i++) {
    image[i].addEventListener("change", function (e) {
        var preview = e;
        oFReader.readAsDataURL(e.target.files[0]);
        oFReader.onload = function (oFREvent) {
            preview.target.previousElementSibling.src = oFREvent.target.result;
        };
    });
}

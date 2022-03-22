function previewImage() {
    const image = document.querySelector("#image-add");
    const preview = document.querySelector("#preview-add");

    const oFReader = new FileReader();
    oFReader.readAsDataURL(image.files[0]);
    oFReader.onload = function (oFREvent) {
        preview.src = oFREvent.target.result;
    };
}

function previewProfile() {
    const image = document.querySelector("#image-profile");
    const preview = document.querySelector("#preview-profile");

    const oFReader = new FileReader();
    oFReader.readAsDataURL(image.files[0]);
    oFReader.onload = function (oFREvent) {
        preview.src = oFREvent.target.result;
    };
}

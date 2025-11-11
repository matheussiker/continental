document.querySelectorAll('.imgs input[type="file"]').forEach(input => {
    input.addEventListener('change', function() {
        const label = document.getElementById("label" + this.id.replace("img", ""));
        const span = label.querySelector("span");

        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                // remove texto e insere imagem
                span.style.display = "none";

                let img = label.querySelector("img");
                if (!img) {
                    img = document.createElement("img");
                    label.appendChild(img);
                }
                img.src = e.target.result;
                img.style.maxWidth = "100%";
                img.style.maxHeight = "100%";
                img.style.borderRadius = "6px";
                img.style.objectFit = "cover";
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            // reset se remover imagem
            span.style.display = "block";
            const img = label.querySelector("img");
            if (img) img.remove();
        }
    });
}); 
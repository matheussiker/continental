<?php 
session_start();
require("conecta.php");

// 🔹 Função para redimensionar imagens com GD
function redimensionarImagem($origem, $destino, $larguraMax, $alturaMax) {
    list($largura, $altura, $tipo) = getimagesize($origem);

    // Mantém a proporção
    $ratio = min($larguraMax / $largura, $alturaMax / $altura);
    $novaLargura = intval($largura * $ratio);
    $novaAltura = intval($altura * $ratio);

    $novaImagem = imagecreatetruecolor($novaLargura, $novaAltura);

    switch ($tipo) {
        case IMAGETYPE_JPEG:
            $imagem = imagecreatefromjpeg($origem);
            break;
        case IMAGETYPE_PNG:
            $imagem = imagecreatefrompng($origem);
            imagealphablending($novaImagem, false);
            imagesavealpha($novaImagem, true);
            break;
        case IMAGETYPE_WEBP:
            $imagem = imagecreatefromwebp($origem);
            break;
        default:
            return false; // formato não suportado
    }

    imagecopyresampled($novaImagem, $imagem, 0, 0, 0, 0, 
        $novaLargura, $novaAltura, $largura, $altura);

    // Salva imagem
    switch ($tipo) {
        case IMAGETYPE_JPEG:
            imagejpeg($novaImagem, $destino, 85);
            break;
        case IMAGETYPE_PNG:
            imagepng($novaImagem, $destino);
            break;
        case IMAGETYPE_WEBP:
            imagewebp($novaImagem, $destino, 85);
            break;
    }

    imagedestroy($imagem);
    imagedestroy($novaImagem);

    return true;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_user = $_SESSION["id_user"];
    $titulo = $_POST["titulo"];
    $data = $_POST["data_viagem"];
    $imagemCapaIndice = $_POST["capa"];
    $imagens = [];
    $imagemCapa = null;

    $uploadDir = "uploads/memorias/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Processar até 14 imagens
    for ($i = 1; $i <= 14; $i++) {
        $inputName = "img$i";
        if (!empty($_FILES[$inputName]["name"])) {
            $ext = pathinfo($_FILES[$inputName]["name"], PATHINFO_EXTENSION);
            $fileName = uniqid("img_") . "." . $ext;
            $destino = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $destino)) {
                // 🔹 Redimensiona a imagem após salvar
                redimensionarImagem($destino, $destino, 800, 600);
                $imagens[$i] = $destino;

                if ((int)$imagemCapaIndice === $i) {
                    $imagemCapa = $destino;
                }
            }
        } else {
            $imagens[$i] = null;
        }
    }

    if (!$imagemCapa) {
        $imagemCapa = $imagens[1];
    }

    // Salva no banco
    $stmt = $mysqli->prepare("
        INSERT INTO memorias (
            id_user, titulo, data_viagem, imagem_capa,
            img1, img2, img3, img4, img5, img6, img7,
            img8, img9, img10, img11, img12, img13, img14
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("isss" . str_repeat("s", 14), 
        $id_user, 
        $titulo, 
        $data,
        $imagemCapa,
        $imagens[1], $imagens[2], $imagens[3], $imagens[4], $imagens[5], $imagens[6], $imagens[7],
        $imagens[8], $imagens[9], $imagens[10], $imagens[11], $imagens[12], $imagens[13], $imagens[14]
    );

    if ($stmt->execute()) {
        header("Location: perfil.php");
        exit;
    } else {
        echo "Erro ao salvar memória: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Continental</title>
    <link rel="stylesheet" href="../css/criar_memoria.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h2 id="logo">CONTINENTAL</h2>
    </header>

    <a class="voltar" href="perfil.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-box-arrow-in-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0z"/>
        <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
        </svg>
    </a>

    <form method="POST" enctype="multipart/form-data">
        <div class="dados">
            <label for="titulo">Nome da Cidade</label>
            <input type="text" name="titulo" id="titulo" required>
            <label for="data_viagem">Data da Viagem</label>
            <input type="date" name="data_viagem" id="data_viagem" required>
        </div>

        <label>Selecione até 14 imagens:</label>

        <div class="imgs">
            <?php for ($i = 1; $i <= 14; $i++): ?>
                <input type="file" name="img<?= $i ?>" id="img<?= $i ?>" accept="image/*">
                <label for="img<?= $i ?>" id="label<?= $i ?>">
                    <span>Upload File <?= $i ?></span>
                </label>
            <?php endfor; ?>
        </div>

        <label for="capa">Escolha a imagem que será a capa:</label>
        <select name="capa" id="capa" required>
            <?php for ($i = 1; $i <= 14; $i++): ?>
                <option value="<?= $i ?>">Imagem <?= $i ?></option>
            <?php endfor; ?>
        </select>

        <button type="submit">Salvar Memória</button>
    </form>

    <!-- 🔹 Script Preview -->
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        for (let i = 1; i <= 14; i++) {
            const input = document.getElementById("img" + i);
            const label = document.getElementById("label" + i);

            input.addEventListener("change", (event) => {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        label.innerHTML = `<img src="${e.target.result}" 
                            style="width:100%; height:100%; object-fit:cover;">`;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorilerim</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    width: 80%;
    margin: 20px auto;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.page-title {
    text-align: center;
    font-size: 32px;
    margin-bottom: 20px;
}

.favorites {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.favorite-item {
    background-color: #fff;
    padding: 20px; /* Padding değerini artırdım */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: calc(50% - 20px);
    box-sizing: border-box;
    display: flex;
    align-items: center;
}

.favorite-image {
    width: 150px; /* Genişliği artırdım */
    height: 150px; /* Yüksekliği artırdım */
    border-radius: 10px;
    margin-right: 20px;
}

.favorite-info {
    flex-grow: 1;
}

.favorite-name {
    font-size: 24px; /* Yazı boyutunu artırdım */
    margin: 0 0 10px;
}

.favorite-price {
    font-size: 22px; /* Yazı boyutunu artırdım */
    color: #ff6f61;
    margin: 0 0 10px;
}

.remove-button {
    padding: 10px 20px;
    background-color: #ff6f61;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.remove-button:hover {
    background-color: #ff3b2f;
}
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Favorilerim</h1>
        <div class="favorites">
            <div class="favorite-item">
                <img src="../images/gingercookie.jpeg" alt="Ürün 1" class="favorite-image">
                <div class="favorite-info">
                    <h2 class="favorite-name">Ürün Adı 1</h2>
                    <p class="favorite-price">₺100</p>
                    <button class="remove-button">Favorilerden Kaldır</button>
                </div>
            </div>

            <div class="favorite-item">
                <img src="../images/salca.jpg" alt="Ürün 2" class="favorite-image">
                <div class="favorite-info">
                    <h2 class="favorite-name">Ürün Adı 2</h2>
                    <p class="favorite-price">₺200</p>
                    <button class="remove-button">Favorilerden Kaldır</button>
                </div>
            </div>
            <div class="favorite-item">
                <img src="../images/sutreceli.jpeg" alt="Ürün 2" class="favorite-image">
                <div class="favorite-info">
                    <h2 class="favorite-name">Ürün Adı 2</h2>
                    <p class="favorite-price">₺200</p>
                    <button class="remove-button">Favorilerden Kaldır</button>
                </div>
            </div>
            <div class="favorite-item">
                <img src="../images/orgu.jpg" alt="Ürün 2" class="favorite-image">
                <div class="favorite-info">
                    <h2 class="favorite-name">Ürün Adı 2</h2>
                    <p class="favorite-price">₺200</p>
                    <button class="remove-button">Favorilerden Kaldır</button>
                </div>
            </div>
            <div class="favorite-item">
                <img src="../images/gingercookie.jpeg" alt="Ürün 2" class="favorite-image">
                <div class="favorite-info">
                    <h2 class="favorite-name">Ürün Adı 2</h2>
                    <p class="favorite-price">₺200</p>
                    <button class="remove-button">Favorilerden Kaldır</button>
                </div>
            </div>
            <div class="favorite-item">
                <img src="../images/salca.jpg" alt="Ürün 2" class="favorite-image">
                <div class="favorite-info">
                    <h2 class="favorite-name">Ürün Adı 2</h2>
                    <p class="favorite-price">₺200</p>
                    <button class="remove-button">Favorilerden Kaldır</button>
                </div>
            </div>
            <div class="favorite-item">
                <img src="../images/sutreceli.jpeg" alt="Ürün 2" class="favorite-image">
                <div class="favorite-info">
                    <h2 class="favorite-name">Ürün Adı 2</h2>
                    <p class="favorite-price">₺200</p>
                    <button class="remove-button">Favorilerden Kaldır</button>
                </div>
            </div>
            <div class="favorite-item">
                <img src="../images/orgu.jpg" alt="Ürün 2" class="favorite-image">
                <div class="favorite-info">
                    <h2 class="favorite-name">Ürün Adı 2</h2>
                    <p class="favorite-price">₺200</p>
                    <button class="remove-button">Favorilerden Kaldır</button>
                </div>
            </div>
            <div class="favorite-item">
                <img src="../images/gingercookie.jpeg" alt="Ürün 2" class="favorite-image">
                <div class="favorite-info">
                    <h2 class="favorite-name">Ürün Adı 2</h2>
                    <p class="favorite-price">₺200</p>
                    <button class="remove-button">Favorilerden Kaldır</button>
                </div>
            </div>

            <!-- Daha fazla favori ürün ekleyebilirsiniz -->
        </div>

    </div>
</body>
</html>
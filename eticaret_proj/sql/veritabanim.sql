CREATE DATABASE e_ticaret CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE e_ticaret;

CREATE TABLE kullanicilar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tam_ad VARCHAR(255) NOT NULL,
    eposta VARCHAR(255) UNIQUE NOT NULL,
    cinsiyet VARCHAR(50),
    dogum_tarihi DATE,
    olusturma_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
    ulke_kodu INT,
    sifre VARCHAR(255) NOT NULL
);

CREATE TABLE ulkeler (
    kod INT PRIMARY KEY AUTO_INCREMENT,
    isim VARCHAR(255) NOT NULL,
    kita_adi VARCHAR(255)
);

CREATE TABLE saticilar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT,
    satici_adi VARCHAR(255) NOT NULL,
    ulke_kodu INT,
    olusturma_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES kullanicilar(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (ulke_kodu) REFERENCES ulkeler(kod) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE kategoriler (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kategori_adi VARCHAR(255) NOT NULL,
    ust_kategori_id INT,
    FOREIGN KEY (ust_kategori_id) REFERENCES kategoriler(id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE urunler (
    id INT PRIMARY KEY AUTO_INCREMENT,
    isim VARCHAR(255) NOT NULL,
    satici_id INT NOT NULL,
    fiyat DECIMAL(10,2) NOT NULL,
    durum VARCHAR(50) DEFAULT 'aktif',
    olusturma_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
    kategori_id INT,
    FOREIGN KEY (satici_id) REFERENCES saticilar(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (kategori_id) REFERENCES kategoriler(id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE siparisler (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kullanici_id INT NOT NULL,
    durum VARCHAR(50) DEFAULT 'beklemede',
    olusturma_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kullanici_id) REFERENCES kullanicilar(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE siparis_ogeleri (
    siparis_id INT,
    urun_id INT,
    miktar INT NOT NULL,
    PRIMARY KEY (siparis_id, urun_id),
    FOREIGN KEY (siparis_id) REFERENCES siparisler(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (urun_id) REFERENCES urunler(id) ON DELETE CASCADE ON UPDATE CASCADE
);

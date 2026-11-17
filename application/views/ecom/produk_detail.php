<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
.product-detail-wrap{max-width:1120px;margin:28px auto;padding:0 18px}
.product-detail-card{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.15fr);gap:28px;background:#fff;border:1px solid #e6e9ed;border-radius:8px;padding:22px}
.product-main-image{width:100%;height:430px;object-fit:contain;background:#f5f6f7;border-radius:5px}
.product-thumb-list{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-top:14px}
.product-thumb{width:76px;height:76px;object-fit:contain;border:1px solid #d9dee4;border-radius:4px;padding:4px;cursor:pointer;background:#fff}
.product-thumb.active{border:2px solid #0d6efd}
.product-detail-info h1{font-size:28px;margin:0 0 12px}
.product-detail-price{font-size:22px;font-weight:700;color:#b77900;margin:10px 0}
.product-meta{line-height:1.9;color:#555}
.product-quantity{margin-top:22px;max-width:420px}
.product-quantity label{display:block;font-weight:600;margin-bottom:8px}
.product-quantity input{width:100%;height:42px;padding:8px 10px;border:1px solid #ced4da;border-radius:4px}
.product-add-button{margin-top:12px;width:100%;padding:12px;background:#0d6efd;color:#fff;border:0;border-radius:4px;cursor:pointer;font-size:15px}
.product-description{margin-top:26px;padding-top:20px;border-top:1px solid #e9ecef;line-height:1.7}
@media(max-width:760px){.product-detail-card{grid-template-columns:1fr}.product-main-image{height:300px}.product-detail-info h1{font-size:23px}}
</style>
<section class="page-banner">
  <div>
    <h1>DETAIL PRODUK</h1>
    <div class="crumb"><a href="<?php echo base_url(); ?>">Home</a> &nbsp;/&nbsp;
      <?php if (!empty($kategori)): ?><a href="<?php echo base_url('produk'); ?>"><?php echo html_escape($kategori['nama']); ?></a> &nbsp;/&nbsp;<?php endif; ?>
      <span><?php echo html_escape($produk['nama']); ?></span>
    </div>
  </div>
</section>
<main class="product-detail-wrap">
  <?php if ($this->session->flashdata('message')): ?>
    <div class="alert"><?php echo html_escape($this->session->flashdata('message')); ?></div>
  <?php endif; ?>
  <div class="product-detail-card">
    <div class="product-gallery">
      <?php
        $gambar_utama = !empty($list_gambar) ? $list_gambar[0]['nama_gambar'] : '';
        $gambar_utama_valid = $gambar_utama && is_file(FCPATH . 'uploads/produk/' . $gambar_utama);
      ?>
      <?php if ($gambar_utama_valid): ?>
        <img id="gambarProdukUtama" class="product-main-image"
             src="<?php echo base_url('uploads/produk/' . rawurlencode($gambar_utama)); ?>"
             alt="<?php echo html_escape($produk['nama']); ?>">
      <?php else: ?>
        <div id="gambarProdukUtama" class="product-main-image" style="display:flex;align-items:center;justify-content:center;color:#6c757d">
          Gambar produk belum tersedia
        </div>
      <?php endif; ?>
      <?php if (!empty($list_gambar)): ?>
      <div class="product-thumb-list" aria-label="Galeri gambar produk">
        <?php foreach ($list_gambar as $index => $gambar):
          $file_gambar = $gambar['nama_gambar'];
          if (!$file_gambar || !is_file(FCPATH . 'uploads/produk/' . $file_gambar)) continue;
        ?>
          <img class="product-thumb<?php echo $index === 0 ? ' active' : ''; ?>"
               src="<?php echo base_url('uploads/produk/' . rawurlencode($file_gambar)); ?>"
               data-full="<?php echo base_url('uploads/produk/' . rawurlencode($file_gambar)); ?>"
               alt="Foto <?php echo html_escape($produk['nama']); ?> <?php echo $index + 1; ?>">
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <div class="product-detail-info">
      <h1><?php echo html_escape($produk['nama']); ?></h1>
      <div class="product-detail-price">Rp <?php echo number_format((float) $produk['harga'], 0, ',', '.'); ?></div>
      <div class="product-meta">
        <div>ID Produk: <?php echo (int) $produk['id_produk']; ?></div>
        <div>STOK: <?php echo (int) $produk['stok']; ?></div>
        <?php if (!empty($kategori)): ?><div>Kategori: <?php echo html_escape($kategori['nama']); ?></div><?php endif; ?>
      </div>
      <div class="product-quantity">
        <?php if ((int) $produk['stok'] > 0): ?>
          <form action="<?php echo base_url('produk/tambah-keranjang'); ?>" method="post">
            <input type="hidden" name="id_produk" value="<?php echo (int) $produk['id_produk']; ?>">
            <label for="jumlahProduk">Jumlah beli</label>
            <input id="jumlahProduk" name="jumlah" type="number" min="1" max="<?php echo (int) $produk['stok']; ?>" value="1" required>
            <button class="product-add-button" type="submit"><i class="fas fa-shopping-cart" aria-hidden="true"></i> Tambah ke keranjang</button>
          </form>
        <?php else: ?>
          <p class="alert">Stok produk sedang habis.</p>
        <?php endif; ?>
      </div>
      <div class="product-description">
        <h3>Deskripsi</h3>
        <p><?php echo nl2br(html_escape($produk['deskripsi'])); ?></p>
      </div>
    </div>
  </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var mainImage = document.getElementById('gambarProdukUtama');
  var thumbs = document.querySelectorAll('.product-thumb');
  thumbs.forEach(function (thumb) {
    thumb.addEventListener('click', function () {
      if (mainImage && thumb.getAttribute('data-full')) {
        if (mainImage.tagName.toLowerCase() !== 'img') {
          var replacement = document.createElement('img');
          replacement.id = 'gambarProdukUtama';
          replacement.className = 'product-main-image';
          replacement.alt = thumb.alt || 'Foto produk';
          mainImage.parentNode.replaceChild(replacement, mainImage);
          mainImage = replacement;
        }
        mainImage.src = thumb.getAttribute('data-full');
        thumbs.forEach(function (item) { item.classList.remove('active'); });
        thumb.classList.add('active');
      }
    });
  });
});
</script>

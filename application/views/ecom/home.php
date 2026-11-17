<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<main class="container-site" id="produk">
<div class="section-title">
  <div>
    <p class="muted" style="margin:0 0 6px">PILIHAN UNTUK ANDA</p>
    <h2>Produk Kami</h2>
    <?php if (!empty($keyword)): ?>
      <p class="muted">Hasil pencarian: "<?php echo html_escape($keyword); ?>" · <a href="<?php echo base_url(); ?>">Hapus pencarian</a></p>
    <?php endif; ?>
  </div>
  <span class="muted"><?php echo count($list_produk); ?> produk tersedia</span>
</div>
<?php if ($this->session->flashdata('message')): ?>
  <div class="alert"><?php echo html_escape($this->session->flashdata('message')); ?></div>
<?php endif; ?>
<?php if (empty($list_produk)): ?>
  <p class="muted">Belum ada produk yang tersedia.</p>
<?php else: ?>
<div class="product-grid">
<?php foreach ($list_produk as $produk):
    $id_produk = (int) $produk['id_produk'];
    $foto = !empty($gambar_produk[$id_produk]) ? $gambar_produk[$id_produk][0]['nama_gambar'] : '';
    $url_detail = base_url('produk/' . $id_produk);
?>
<article class="product-card">
  <a href="<?php echo $url_detail; ?>" class="product-image" style="display:flex;text-decoration:none;color:inherit" aria-label="Lihat detail <?php echo html_escape($produk['pd_nama']); ?>">
    <?php if ($foto && is_file(FCPATH . 'uploads/produk/' . $foto)): ?>
      <img src="<?php echo base_url('uploads/produk/' . rawurlencode($foto)); ?>" alt="<?php echo html_escape($produk['pd_nama']); ?>">
    <?php else: ?>
      <span class="muted">Gambar produk belum tersedia</span>
    <?php endif; ?>
  </a>
  <div class="product-info">
    <span class="pill"><?php echo html_escape($produk['kt_nama']); ?></span>
    <h3 style="margin-top:12px"><a href="<?php echo $url_detail; ?>" style="color:inherit;text-decoration:none"><?php echo html_escape($produk['pd_nama']); ?></a></h3>
    <div class="d-flex justify-content-between align-items-center" style="display:flex;justify-content:space-between;align-items:center;color:#343a40">
      <small>Rp <?php echo number_format((float) $produk['harga'], 0, ',', '.'); ?></small>
      <small><?php echo (int) $produk['terjual']; ?> terjual</small>
    </div>
    <p style="margin:12px 0 0"><a class="btn-shop" style="padding:8px 12px;font-size:13px" href="<?php echo $url_detail; ?>">Lihat Detail</a></p>
  </div>
</article>
<?php endforeach; ?>
</div>
<?php endif; ?>
<div class="container-site" style="text-align:center;padding-bottom:0">
  <a href="<?php echo base_url('produk'); ?>" class="btn btn-outline-primary" style="display:inline-block;border:1px solid #0d6efd;color:#0d6efd;padding:9px 18px;border-radius:4px;text-decoration:none">Lihat Semua Produk</a>
</div>
</main>

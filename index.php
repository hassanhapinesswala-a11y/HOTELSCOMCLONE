<?php
// index.php
?>
<!doctype html>
<html lang="ur">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>HotelsClone - تلاش کریں</title>
  <style>
    /* Internal CSS - sleek, modern */
    :root{--accent:#ff5a5f;--muted:#6b7280;--bg:#f6f7fb;--card:#ffffff}
    *{box-sizing:border-box;font-family: 'Segoe UI', Roboto, Arial, sans-serif}
    body{margin:0;background:linear-gradient(180deg,#eef2ff,#f6f7fb);color:#111}
    header{padding:36px;text-align:center}
    .brand{font-size:28px;font-weight:700;color:var(--accent)}
    .hero{max-width:1100px;margin:20px auto;padding:28px;background:var(--card);border-radius:18px;box-shadow:0 10px 30px rgba(17,24,39,0.08)}
    .search-row{display:flex;gap:12px;flex-wrap:wrap}
    .input, .btn{padding:14px 16px;border-radius:12px;border:1px solid #e6e7ee}
    .input{flex:1;min-width:160px}
    .btn{background:var(--accent);color:#fff;border:none;cursor:pointer;font-weight:600}
    .features{display:flex;gap:16px;justify-content:space-between;margin-top:18px;flex-wrap:wrap}
    .card{background:linear-gradient(180deg,#fff,#fbfdff);padding:12px;border-radius:12px;flex:1;min-width:220px;box-shadow:0 6px 18px rgba(17,24,39,0.04)}
    .card h4{margin:6px 0;color:#0f172a}
    footer{margin-top:28px;text-align:center;color:var(--muted);padding:20px}
    @media(max-width:720px){.search-row{flex-direction:column}}
  </style>
</head>
<body>
<header>
  <div class="brand">HotelsClone</div>
  <div style="margin-top:6px;color:#6b7280">Search • Compare • Book — بہترین ہوٹل ڈیلز</div>
</header>
 
<main class="hero">
  <div style="font-weight:700;font-size:20px;margin-bottom:12px">اپنی تلاش کریں</div>
  <div class="search-row">
    <input id="destination" class="input" placeholder="شہر یا مقام لکھیں (مثال: Karachi)">
    <input id="checkin" class="input" type="date">
    <input id="checkout" class="input" type="date">
    <select id="guests" class="input" style="max-width:120px">
      <option value="1">1 مہمان</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4+</option>
    </select>
    <button id="searchBtn" class="btn">تلاش کریں</button>
  </div>
 
  <div class="features" style="margin-top:18px">
    <div class="card"><h4>Featured</h4><p style="color:var(--muted)">ہفتے کی بہترین ڈیلز</p></div>
    <div class="card"><h4>Top Rated</h4><p style="color:var(--muted)">اعلی درجہ بندی والے ہوٹلز</p></div>
    <div class="card"><h4>Family Friendly</h4><p style="color:var(--muted)">خاندانی سہولیات کے ساتھ</p></div>
  </div>
</main>
 
<footer>© <?=date('Y')?> HotelsClone — Demo</footer>
 
<script>
  // JS used only for redirecting to results page (no PHP redirection)
  document.getElementById('searchBtn').addEventListener('click', function(){
    const dest = encodeURIComponent(document.getElementById('destination').value.trim() || '');
    const checkin = document.getElementById('checkin').value;
    const checkout = document.getElementById('checkout').value;
    const guests = document.getElementById('guests').value;
    // build query
    const params = new URLSearchParams();
    if(dest) params.append('city', dest);
    if(checkin) params.append('checkin', checkin);
    if(checkout) params.append('checkout', checkout);
    params.append('guests', guests);
    // redirect client-side
    window.location.href = 'results.php?' + params.toString();
  });
</script>
</body>
</html>

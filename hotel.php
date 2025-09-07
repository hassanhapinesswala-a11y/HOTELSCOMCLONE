?php
require_once 'db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $mysqli->prepare("SELECT * FROM hotels WHERE id = ?");
$stmt->bind_param('i',$id);
$stmt->execute();
$res = $stmt->get_result();
$hotel = $res->fetch_assoc();
if(!$hotel){
  echo "Hotel not found.";
  exit;
}
?>
<!doctype html>
<html lang="ur">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=htmlspecialchars($hotel['name'])?> — HotelsClone</title>
<style>
  body{font-family:Arial,Helvetica,sans-serif;margin:0;background:#f7fafc;color:#0f172a}
  .wrap{max-width:1000px;margin:18px auto;padding:18px}
  .top{display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap}
  .img{flex:1;min-width:300px;border-radius:12px;overflow:hidden}
  .img img{width:100%;height:320px;object-fit:cover}
  .info{flex:1;min-width:280px;background:#fff;padding:16px;border-radius:12px;box-shadow:0 10px 30px rgba(2,6,23,0.06)}
  .price{font-size:22px;color:#ff5a5f;font-weight:800}
  .book-form{margin-top:12px;display:flex;flex-direction:column;gap:8px}
  .input, .btn{padding:10px;border-radius:10px;border:1px solid #e6e7ee}
  .btn{background:#ff5a5f;color:#fff;border:none;cursor:pointer;font-weight:700}
  .amen{margin-top:8px;color:#6b7280;font-size:14px}
</style>
</head>
<body>
<div class="wrap">
  <div style="font-weight:800;font-size:20px"><?=htmlspecialchars($hotel['name'])?></div>
  <div class="top">
    <div class="img"><img src="<?=htmlspecialchars($hotel['image'] ?: 'images/placeholder.jpg')?>" alt=""></div>
    <div class="info">
      <div style="color:#374151"><?=htmlspecialchars($hotel['address'])?></div>
      <div class="price">Rs <?= number_format($hotel['price']) ?> / night</div>
      <div style="margin-top:8px;color:#111"><?=nl2br(htmlspecialchars($hotel['description']))?></div>
      <div class="amen"><strong>Amenities:</strong> <?=htmlspecialchars($hotel['amenities'])?></div>
 
      <form action="book.php" method="post" class="book-form" onsubmit="return validateDates()">
        <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">
        <input class="input" name="guest_name" placeholder="Your name" required>
        <input class="input" name="email" type="email" placeholder="Email" required>
        <div style="display:flex;gap:8px">
          <input class="input" id="checkin" name="checkin" type="date" required>
          <input class="input" id="checkout" name="checkout" type="date" required>
        </div>
        <input class="input" name="guests" type="number" min="1" value="1" required>
        <input type="hidden" id="pricePerNight" value="<?= $hotel['price'] ?>">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:8px">
          <div style="color:#6b7280">Total est: <span id="total">Rs <?= number_format($hotel['price']) ?></span></div>
          <button class="btn" type="submit">Confirm Booking</button>
        </div>
      </form>
 
    </div>
  </div>
</div>
 
<script>
  function daysBetween(a,b){
    const da = new Date(a), db = new Date(b);
    const diff = (db - da) / (1000*60*60*24);
    return Math.max(0, Math.ceil(diff));
  }
  const price = parseFloat(document.getElementById('pricePerNight').value) || 0;
  function updateTotal(){
    const c1 = document.getElementById('checkin').value;
    const c2 = document.getElementById('checkout').value;
    let nights = 1;
    if(c1 && c2){ nights = daysBetween(c1,c2) || 1; }
    document.getElementById('total').innerText = 'Rs ' + (nights * price).toLocaleString();
  }
  document.getElementById('checkin').addEventListener('change', updateTotal);
  document.getElementById('checkout').addEventListener('change', updateTotal);
  function validateDates(){
    const c1 = document.getElementById('checkin').value;
    const c2 = document.getElementById('checkout').value;
    if(!c1 || !c2) { alert('براہِ کرم تاریخ منتخب کریں'); return false; }
    if(new Date(c2) <= new Date(c1)){ alert('checkout تاریخ checkin کے بعد ہونی چاہئے'); return false; }
    return true;
  }
</script>
</body>
</html>
 

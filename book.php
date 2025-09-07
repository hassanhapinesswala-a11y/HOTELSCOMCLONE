<?php
require_once 'db.php';
 
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
  echo "Invalid request";
  exit;
}
 
// sanitize
$hotel_id = isset($_POST['hotel_id']) ? (int)$_POST['hotel_id'] : 0;
$guest_name = trim($_POST['guest_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$checkin = $_POST['checkin'] ?? '';
$checkout = $_POST['checkout'] ?? '';
$guests = isset($_POST['guests']) ? (int)$_POST['guests'] : 1;
 
// basic validation
if(!$hotel_id || !$guest_name || !$email || !$checkin || !$checkout){
  echo "Missing data";
  exit;
}
 
// fetch hotel price
$stmt = $mysqli->prepare("SELECT price FROM hotels WHERE id = ?");
$stmt->bind_param('i',$hotel_id);
$stmt->execute();
$res = $stmt->get_result();
if($res->num_rows===0){ echo "Hotel not found"; exit; }
$row = $res->fetch_assoc();
$price = (float)$row['price'];
 
// calculate nights
$checkin_dt = new DateTime($checkin);
$checkout_dt = new DateTime($checkout);
$interval = $checkin_dt->diff($checkout_dt);
$nights = max(1, (int)$interval->days);
$total = $nights * $price;
 
// insert booking (prepared)
$ins = $mysqli->prepare("INSERT INTO bookings (hotel_id, guest_name, email, checkin, checkout, guests, total_price) VALUES (?,?,?,?,?,?,?)");
$ins->bind_param('issssid', $hotel_id, $guest_name, $email, $checkin, $checkout, $guests, $total);
$ok = $ins->execute();
 
$booking_id = $mysqli->insert_id;
?>
<!doctype html>
<html lang="ur">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Booking Confirmed</title>
<style>
 body{font-family:Arial,Helvetica,sans-serif;background:#f3f6fb;padding:36px}
 .card{max-width:700px;margin:0 auto;background:#fff;padding:24px;border-radius:14px;box-shadow:0 16px 40px rgba(2,6,23,0.06)}
 .succ{color:#065f46;font-weight:800}
 .btn{padding:10px 14px;border-radius:10px;border:none;background:#ff5a5f;color:#fff;cursor:pointer}
</style>
</head>
<body>
<div class="card">
  <?php if($ok): ?>
    <div style="font-size:22px" class="succ">Booking Confirmed!</div>
    <p>شکریہ <?=htmlspecialchars($guest_name)?> — آپ کی بکنگ نمبر <strong>#<?= $booking_id ?></strong> بن گئی ہے.</p>
    <p>Hotel ID: <?= $hotel_id ?> &nbsp; Nights: <?= $nights ?> &nbsp; Total: Rs <?= number_format($total) ?></p>
    <p>ہم نے کنفرمیشن آپ کے ای میل (<?=htmlspecialchars($email)?>) پر بھی بھیج دی ہے (demo).</p>
    <div style="margin-top:14px">
      <button class="btn" onclick="window.location='index.php'">واپس جائیں</button>
    </div>
  <?php else: ?>
    <div style="color:#991b1b;font-weight:700">Booking failed — دوبارہ کوشش کریں</div>
    <div style="margin-top:10px"><button class="btn" onclick="window.history.back()">Back</button></div>
  <?php endif; ?>
</div>
</body>
</html>

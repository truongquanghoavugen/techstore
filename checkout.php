<?php
$pageTitle = 'Thanh toán';
require __DIR__ . '/app/partials/header.php';
?>

<main class="checkout-page">
  <header class="checkout-header">
    <a class="brand" href="/trang-chu">GearZone <span>Store</span></a>
    <a class="back-link" href="/gio-hang"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Quay lại giỏ hàng</a>
  </header>

  <div class="checkout-layout">
    <section class="checkout-card">
      <p class="eyebrow">Secure checkout</p>
      <h1>Thông tin đặt hàng</h1>
      <p class="muted">Kiểm tra thông tin trước khi hoàn tất đơn hàng.</p>

      <form id="checkout-form" novalidate>
        <h2>Thông tin nhận hàng</h2>
        <div class="form-grid">
          <label>Họ và tên *<input id="customer-name" required autocomplete="name" placeholder="Nguyễn Văn A"></label>
          <label>Số điện thoại *<input id="customer-phone" required inputmode="tel" autocomplete="tel" placeholder="0901234567"></label>
        </div>
        <label>Địa chỉ nhận hàng *<input id="customer-address" required autocomplete="street-address" placeholder="Số nhà, đường, phường/xã, tỉnh/thành"></label>
        <label>Ghi chú đơn hàng <textarea id="customer-note" rows="3" placeholder="Ghi chú giao hàng nếu có"></textarea></label>

        <h2>Phương thức thanh toán</h2>
        <div class="payment-options">
          <label class="payment-option"><input type="radio" name="payment" value="cod" checked><span><strong>Thanh toán khi nhận hàng</strong><small>Thanh toán tiền mặt cho đơn vị vận chuyển</small></span></label>
          <label class="payment-option"><input type="radio" name="payment" value="bank"><span><strong>Chuyển khoản ngân hàng</strong><small>Chuyển khoản theo thông tin đơn hàng</small></span></label>
        </div>
        <p class="error" id="form-error" role="alert"></p>
        <button class="submit-button" type="submit">Đặt hàng</button>
      </form>
    </section>

    <aside class="order-card">
      <h2>Đơn hàng của bạn</h2>
      <div id="order-items"></div>
      <div class="total-row"><span>Tạm tính</span><strong id="subtotal">0 đ</strong></div>
      <div class="total-row"><span>Giảm giá</span><strong class="discount" id="discount">-0 đ</strong></div>
      <div class="total-row"><span>Phí vận chuyển</span><strong id="shipping">0 đ</strong></div>
      <div class="total-final"><span>Tổng thanh toán</span><strong id="total">0 đ</strong></div>
    </aside>
  </div>

  <section class="success-card" id="success-card" role="dialog" aria-modal="true" aria-labelledby="success-title" tabindex="-1" hidden>
    <div class="success-icon"><i class="fa-solid fa-check" aria-hidden="true"></i></div>
    <p class="eyebrow">Order complete</p>
    <h1 id="success-title">Đặt hàng thành công</h1>
    <p>Cảm ơn bạn. Mã đơn hàng của bạn là <strong id="order-code"></strong>.</p>
    
    <div id="bank-message" class="bank-message" hidden></div>
    <div class="qr-payment" id="qr-payment" hidden>
      <strong>Quét mã QR để chuyển khoản</strong>
      <img id="payment-qr" src="" alt="Mã QR thanh toán">
      <small>Nội dung chuyển khoản: mã đơn hàng</small>
    </div>
    
    <a class="submit-button link-button" href="/san-pham">Tiếp tục mua sắm</a>
  </section>
</main>

<style>
  :root { --ink:#172033; --muted:#687386; --line:#e7eaf0; --soft:#f6f8fb; --brand:#e85d3f; --brand-dark:#c94128; --success:#198754; --danger:#c0392b; }
  * { box-sizing:border-box; }
  body { margin:0; background:linear-gradient(135deg,#fff7f1,#f7f8fc 50%,#eef5f2); color:var(--ink); font:15px/1.5 Inter,ui-sans-serif,system-ui,sans-serif; }
  input,textarea,button { font:inherit; } .checkout-page { width:min(1120px,calc(100% - 32px)); margin:auto; padding-bottom:50px; }
  .checkout-header { padding:28px 0; display:flex; justify-content:space-between; align-items:center; gap:15px; }
  .brand { color:var(--ink); text-decoration:none; font-size:21px; font-weight:850; } .brand span { color:var(--brand); }
  .back-link { color:var(--brand-dark); text-decoration:none; font-weight:700; } .checkout-layout { display:grid; grid-template-columns:minmax(0,1.4fr) minmax(290px,.8fr); gap:22px; align-items:start; }
  .checkout-card,.order-card,.success-card { background:rgba(255,255,255,.94); border:1px solid var(--line); border-radius:16px; padding:25px; box-shadow:0 12px 35px rgba(42,47,61,.06); }
  .eyebrow { margin:0 0 4px; color:var(--brand); font-size:12px; font-weight:800; letter-spacing:1.5px; text-transform:uppercase; } h1 { margin:0 0 5px; font-size:clamp(28px,4vw,40px); letter-spacing:-1px; } h2 { margin:25px 0 14px; font-size:18px; } .checkout-card h2:first-of-type { margin-top:25px; } .muted,small { color:var(--muted); }
  label { display:block; margin:13px 0; color:#3f4958; font-weight:700; } input,textarea { display:block; width:100%; margin-top:6px; padding:12px; border:1px solid #d9dee7; border-radius:8px; color:var(--ink); background:#fff; outline:0; resize:vertical; } input:focus,textarea:focus { border-color:var(--brand); box-shadow:0 0 0 3px rgba(232,93,63,.1); }
  .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:15px; } .payment-option { display:flex; align-items:flex-start; gap:11px; padding:13px; border:1px solid var(--line); border-radius:9px; cursor:pointer; } .payment-option input { width:18px; margin:2px 0 0; accent-color:var(--brand); } .payment-option span { display:flex; flex-direction:column; gap:2px; } .payment-option small { font-weight:400; }
  .submit-button { display:block; width:100%; margin-top:20px; padding:13px 16px; border:0; border-radius:9px; background:var(--brand); color:#fff; text-align:center; text-decoration:none; font-weight:800; cursor:pointer; } .submit-button:hover { background:var(--brand-dark); } .error { min-height:22px; margin:12px 0 0; color:var(--danger); font-weight:700; }
  .order-card { position:sticky; top:18px; } .order-card h2 { margin-top:0; } .order-item { display:grid; grid-template-columns:48px 1fr auto; gap:10px; align-items:center; padding:12px 0; border-bottom:1px solid var(--line); } .order-item img { width:48px; height:48px; object-fit:cover; border-radius:8px; } .order-item strong { font-size:13px; } .order-item small { display:block; font-weight:400; } .total-row,.total-final { display:flex; justify-content:space-between; gap:12px; margin-top:13px; color:var(--muted); } .total-row strong { color:var(--ink); } .discount { color:var(--success)!important; } .total-final { border-top:1px solid var(--line); margin-top:17px; padding-top:17px; color:var(--ink); font-weight:800; } .total-final strong { color:var(--brand-dark); font-size:22px; } .success-card { position:fixed; z-index:20; inset:0; width:min(650px,calc(100% - 32px)); height:max-content; max-height:calc(100vh - 32px); overflow:auto; margin:auto; text-align:center; } .success-card:not([hidden]) { box-shadow:0 0 0 100vmax rgba(23,32,51,.58),0 20px 60px rgba(23,32,51,.3); } .success-card[hidden] { display:none; } .success-icon { width:48px; height:48px; margin:0 auto 12px; border-radius:50%; display:grid; place-items:center; background:#dff4e8; color:var(--success); font-size:27px; font-weight:900; } .bank-message { margin-top:15px; padding:13px; border-radius:8px; background:var(--soft); text-align:left; }
  @media (max-width:760px) { .checkout-layout { grid-template-columns:1fr; } .order-card { position:static; grid-row:1; } .form-grid { grid-template-columns:1fr; gap:0; } .checkout-header { padding-top:18px; } }
  .qr-payment { display:flex; flex-direction:column; align-items:center; gap:7px; margin:16px 0; padding:14px; border:1px solid var(--line); border-radius:10px; background:var(--soft); } .qr-payment img { width:190px; height:190px; padding:7px; background:#fff; border-radius:8px; } .qr-payment small { font-weight:400; }
</style>

<script>
  const money = value => new Intl.NumberFormat('vi-VN').format(Math.max(0, value)) + ' đ';
  const fallbackItems = [{ id:1, name:'Laptop Gaming ASUS ROG Strix G16', variant:'RAM 16GB | SSD 512GB | RTX 4060', price:24990000, qty:1, img:'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=150' }];
  const checkoutData = JSON.parse(sessionStorage.getItem('checkoutData') || 'null') || { items:fallbackItems, discount:0 };
  const items = checkoutData.items || [];
  const subtotal = items.reduce((sum,item) => sum + item.price * item.qty, 0);
  const discount = Math.min(Number(checkoutData.discount) || 0, subtotal);
  const shipping = subtotal ? 30000 : 0;
  const total = subtotal - discount + shipping;
  
  document.getElementById('order-items').innerHTML = items.length ? items.map(item => `<div class="order-item"><img src="${item.img}" alt=""><span>${item.name}<small>${item.variant} · SL: ${item.qty}</small></span><strong>${money(item.price * item.qty)}</strong></div>`).join('') : '<p class="muted">Không có sản phẩm để thanh toán.</p>';
  document.getElementById('subtotal').textContent = money(subtotal);
  document.getElementById('discount').textContent = '-' + money(discount);
  document.getElementById('shipping').textContent = shipping ? money(shipping) : '0 đ';
  document.getElementById('total').textContent = money(total);

  document.getElementById('checkout-form').addEventListener('submit', async event => {
    event.preventDefault();
    const error = document.getElementById('form-error');
    error.textContent = '';
    
    const name = document.getElementById('customer-name').value.trim();
    const phone = document.getElementById('customer-phone').value.trim().replace(/[\s.-]/g, '');
    const address = document.getElementById('customer-address').value.trim();
    const namePattern = /^[\p{L}][\p{L}\s.'-]{1,79}$/u;
    
    if (!name || !phone || !address) { error.textContent = 'Vui lòng điền đầy đủ các trường bắt buộc.'; return; }
    if (!namePattern.test(name) || name.replace(/\s/g, '').length < 2) { error.textContent = 'Họ và tên cần có ít nhất 2 ký tự chữ.'; return; }
    if (!/^0\d{9,10}$/.test(phone)) { error.textContent = 'Số điện thoại phải có 10 hoặc 11 chữ số và bắt đầu bằng số 0.'; return; }
    if (address.length < 10) { error.textContent = 'Địa chỉ cần có ít nhất 10 ký tự để giao hàng.'; return; }
    if (!items.length) { error.textContent = 'Không có sản phẩm để thanh toán.'; return; }

    const paymentMethod = document.querySelector('input[name="payment"]:checked')?.value || 'cod';
    const submitButton = document.querySelector('#checkout-form .submit-button');
    submitButton.disabled = true;

    let orderData;
    try {
      const response = await fetch('/trang-chu/app/api/create-order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          customer_name: name,
          customer_phone: phone,
          customer_address: address,
          note: document.getElementById('customer-note').value.trim(),
          payment_method: paymentMethod,
          items,
          total_amount: total
        })
      });
      orderData = await response.json();
      if (!response.ok || !orderData.success) {
        throw new Error(orderData.message || 'Không thể lưu đơn hàng.');
      }
    } catch (requestError) {
      error.textContent = requestError.message;
      submitButton.disabled = false;
      return;
    }

    const orderCode = orderData.order_code;
    document.getElementById('order-code').textContent = '#' + orderCode;
    
    const bankMessage = document.getElementById('bank-message');
    const qrPayment = document.getElementById('qr-payment');
    const paymentQr = document.getElementById('payment-qr');
    // KIỂM TRA PHƯƠNG THỨC THANH TOÁN
    if (paymentMethod === 'bank') {
      bankMessage.style.display = 'block';
      qrPayment.style.display = 'flex';
      bankMessage.hidden = false;
      qrPayment.hidden = false;
      
      bankMessage.innerHTML = `Vui lòng chuyển <strong>${money(total)}</strong> với nội dung <strong>${orderCode}</strong> vào tài khoản cửa hàng.`;
      paymentQr.src = `https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${encodeURIComponent(`GEARZONE STORE|${orderCode}|${total}|Thanh toan don hang`)}`;
    } else {
      // NẾU LÀ COD (TIỀN MẶT) -> DẶM ẨN TRIỆT ĐỂ BẰNG CSS STYLE
      bankMessage.style.display = 'none';
      qrPayment.style.display = 'none';
      bankMessage.hidden = true;
      qrPayment.hidden = true;
      paymentQr.removeAttribute('src');
    }

    document.querySelector('.checkout-layout').hidden = true;
    document.getElementById('success-card').hidden = false;
    document.getElementById('success-card').focus();
    const storedCart = JSON.parse(localStorage.getItem('gearzoneCart') || '[]');
    const orderedIds = new Set(items.map(item => Number(item.id)));
    localStorage.setItem('gearzoneCart', JSON.stringify(storedCart.filter(item => !orderedIds.has(Number(item.id)))));
    sessionStorage.removeItem('checkoutData');
  });
</script>
<?php require __DIR__ . '/app/partials/footer.php'; ?>
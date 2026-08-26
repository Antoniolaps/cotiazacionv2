<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Display - Dual Screen POS</title>
    <!-- Use Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/pos.css') ?>">
</head>
<div class="pos-single-screen">
    <div class="pos-header-title">Su Producto</div>
    
    <div class="pos-welcome" id="ad-content">
        <h1 class="welcome-title">¡Bienvenido!</h1>
        <p class="welcome-subtitle">Escaneé su tarjeta Productos.</p>
    </div>
    
    <div class="pos-items-area" id="customer-items">
        <div class="waiting-text">Esperando artículos...</div>
    </div>
    
    <div class="pos-price-area">
        <div class="price-label">Precio</div>
        <div class="price-amount" id="customer-total-amount">$0.00</div>
    </div>
</div>

<script>
    const customerItemsContainer = document.getElementById('customer-items');
    const totalAmount = document.getElementById('customer-total-amount');
    const adContent = document.getElementById('ad-content');

    function updateDisplay(data) {
        if (!data || !data.cart || data.cart.length === 0) {
            customerItemsContainer.innerHTML = '<div class="waiting-text">Esperando artículos...</div>';
            totalAmount.innerText = '$0.00';
            
            adContent.innerHTML = `
                <h1 class="welcome-title">¡Bienvenido!</h1>
                <p class="welcome-subtitle">Escaneé su tarjeta Productos.</p>
            `;
            return;
        }

        customerItemsContainer.innerHTML = '';
        data.cart.forEach(item => {
            const el = document.createElement('div');
            el.className = 'customer-item-centered';
            el.innerHTML = `
                <div>
                    <span class="qty">${item.qty}x</span>
                    <span>${item.name}</span>
                </div>
                <div>$${(item.price * item.qty).toFixed(2)}</div>
            `;
            customerItemsContainer.appendChild(el);
        });

        totalAmount.innerText = data.total;
        
        const safeTotal = encodeURIComponent(data.total);
        adContent.innerHTML = `
            <div style="background: white; padding: 15px; border-radius: 15px; display: inline-block; margin-bottom: 15px;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=PAGO:${safeTotal}" alt="QR Code">
            </div>
            <h2 style="color: white; font-weight: 700; margin-bottom: 5px;">Escanee para pagar</h2>
            <p style="color: #9ca3af;">Compatible con su App del Banco</p>
        `;
    }

    window.addEventListener('storage', (event) => {
        if (event.key === 'pos_customer_data') {
            const data = JSON.parse(event.newValue);
            updateDisplay(data);
        }
    });

    const initialData = localStorage.getItem('pos_customer_data');
    if (initialData) {
        updateDisplay(JSON.parse(initialData));
    }
</script>
</body>
</html>

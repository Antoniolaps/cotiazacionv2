/**
 * PosTerminalComponent - Componente Javascript Basado en Clases (Arquitectura Estilo React Component)
 * Maneja el estado interno (state), la reactividad (setState) y los eventos del POS.
 */
class PosTerminalComponent {
    constructor() {
        // Estado del componente (React-like state)
        this.state = {
            cart: [],
            products: [],
            subtotal: 0,
            itbms: 0,
            total: 0,
            montoRecibido: 0,
            cambio: 0,
            metodoPago: 'efectivo',
            clienteId: null,
            loading: false
        };

        this.itbmsRate = 0.07;
        this.init();
    }

    /**
     * Equivalente a ComponentDidMount: Vincula eventos del DOM y carga inicial
     */
    init() {
        this.bindEvents();
        this.fetchProducts('');
    }

    /**
     * Actualiza el estado reactivo y dispara el re-renderizado
     */
    setState(newState) {
        this.state = { ...this.state, ...newState };
        this.render();
    }

    bindEvents() {
        const searchInput = document.getElementById('search-input');
        const categoriaSelect = document.getElementById('categoria-select');
        const metodoPagoSelect = document.getElementById('metodo-pago');
        const montoRecibidoInput = document.getElementById('monto-recibido');
        const btnClear = document.getElementById('btn-clear-cart');
        const btnCompletar = document.getElementById('btn-completar-venta');

        if (searchInput) {
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.fetchProducts(e.target.value.trim());
                }
            });
        }

        if (categoriaSelect) {
            categoriaSelect.addEventListener('change', (e) => {
                this.fetchProducts(searchInput?.value.trim() || '', e.target.value);
            });
        }

        if (metodoPagoSelect) {
            metodoPagoSelect.addEventListener('change', (e) => {
                this.setState({ metodoPago: e.target.value });
            });
        }

        if (montoRecibidoInput) {
            montoRecibidoInput.addEventListener('input', (e) => {
                const monto = parseFloat(e.target.value) || 0;
                const cambio = Math.max(0, monto - this.state.total);
                this.setState({ montoRecibido: monto, cambio: cambio });
            });
        }

        if (btnClear) {
            btnClear.addEventListener('click', () => this.clearCart());
        }

        if (btnCompletar) {
            btnCompletar.addEventListener('click', () => this.completarVenta());
        }
    }

    async fetchProducts(q = '', categoriaId = 0) {
        try {
            const res = await fetch(`/sistemSIU/public/pos/productos?q=${encodeURIComponent(q)}&categoria_id=${categoriaId}`);
            const data = await res.json();
            this.setState({ products: data });
        } catch (err) {
            console.error('Error al cargar productos:', err);
        }
    }

    addToCart(product) {
        const existing = this.state.cart.find(item => item.id === product.id);
        let newCart = [];

        if (existing) {
            if (existing.cantidad + 1 > product.stock) {
                alert(`Stock máximo alcanzado para ${product.nombre} (${product.stock})`);
                return;
            }
            newCart = this.state.cart.map(item =>
                item.id === product.id ? { ...item, cantidad: item.cantidad + 1 } : item
            );
        } else {
            if (product.stock < 1) {
                alert(`Sin stock disponible para ${product.nombre}`);
                return;
            }
            newCart = [...this.state.cart, { id: product.id, nombre: product.nombre, precio: product.precio_venta, cantidad: 1, stock: product.stock }];
        }

        this.calculateTotals(newCart);
    }

    removeFromCart(productId) {
        const newCart = this.state.cart.filter(item => item.id !== productId);
        this.calculateTotals(newCart);
    }

    updateQuantity(productId, cantidad) {
        const cant = parseInt(cantidad) || 1;
        const newCart = this.state.cart.map(item => {
            if (item.id === productId) {
                if (cant > item.stock) {
                    alert(`Stock máximo es ${item.stock}`);
                    return { ...item, cantidad: item.stock };
                }
                return { ...item, cantidad: cant };
            }
            return item;
        });
        this.calculateTotals(newCart);
    }

    calculateTotals(cart) {
        const subtotal = cart.reduce((acc, item) => acc + (item.cantidad * item.precio), 0);
        const itbms = Math.round(subtotal * this.itbmsRate * 100) / 100;
        const total = subtotal + itbms;
        const cambio = Math.max(0, this.state.montoRecibido - total);

        this.setState({ cart, subtotal, itbms, total, cambio });
    }

    clearCart() {
        this.setState({ cart: [], subtotal: 0, itbms: 0, total: 0, cambio: 0, montoRecibido: 0 });
        const montoInput = document.getElementById('monto-recibido');
        if (montoInput) montoInput.value = '';
    }

    async completarVenta() {
        if (this.state.cart.length === 0) {
            alert('El carrito está vacío');
            return;
        }

        if (this.state.metodoPago === 'efectivo' && this.state.montoRecibido < this.state.total - 0.005) {
            alert('Monto recibido insuficiente');
            return;
        }

        const clienteId = document.getElementById('cliente-select')?.value || null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            const res = await fetch('/sistemSIU/public/pos/vender', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    cliente_id: clienteId,
                    metodo_pago: this.state.metodoPago,
                    monto_recibido: this.state.montoRecibido,
                    items: this.state.cart
                })
            });

            const data = await res.json();

            if (data.ok) {
                alert(`¡Venta Exitosa!\nFactura: ${data.factura}\nTotal: $${data.total.toFixed(2)}\nCambio: $${data.cambio.toFixed(2)}`);
                this.clearCart();
                this.fetchProducts('');
            } else {
                alert('Error: ' + (data.error || 'No se pudo procesar la venta'));
            }
        } catch (err) {
            console.error('Error al procesar venta:', err);
            alert('Error de conexión con el servidor.');
        }
    }

    /**
     * Renderizado reactivo del DOM según el estado actual
     */
    render() {
        // Renderizar Grid de Productos
        const grid = document.getElementById('product-grid');
        if (grid) {
            if (this.state.products.length === 0) {
                grid.innerHTML = `<div class="col-12 text-center text-muted py-5"><p>No se encontraron productos disponibles.</p></div>`;
            } else {
                grid.innerHTML = this.state.products.map(p => `
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm product-card ${p.stock <= 0 ? 'opacity-50' : ''}" style="cursor: pointer;" onclick="posApp.addToCart(${JSON.stringify(p).replace(/"/g, '&quot;')})">
                            <div class="card-body p-3">
                                <span class="badge bg-secondary mb-1">${p.categoria_nombre || 'General'}</span>
                                <h6 class="fw-bold mb-1 text-truncate">${p.nombre}</h6>
                                <small class="text-muted d-block">Código: ${p.codigo}</small>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="fs-5 fw-bold text-success">$${p.precio_venta.toFixed(2)}</span>
                                    <span class="badge ${p.stock > 5 ? 'bg-info' : 'bg-warning'} text-dark">Stock: ${p.stock}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        }

        // Renderizar Carrito
        const cartItems = document.getElementById('cart-items');
        if (cartItems) {
            if (this.state.cart.length === 0) {
                cartItems.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">El carrito está vacío</td></tr>`;
            } else {
                cartItems.innerHTML = this.state.cart.map(item => `
                    <tr>
                        <td class="fw-semibold text-truncate" style="max-width: 120px;">${item.nombre}</td>
                        <td>
                            <input type="number" min="1" max="${item.stock}" value="${item.cantidad}" class="form-control form-control-sm" onchange="posApp.updateQuantity(${item.id}, this.value)">
                        </td>
                        <td>$${item.precio.toFixed(2)}</td>
                        <td class="fw-bold">$${(item.cantidad * item.precio).toFixed(2)}</td>
                        <td>
                            <button class="btn btn-sm btn-link text-danger p-0" onclick="posApp.removeFromCart(${item.id})"><i class="bi bi-x-circle-fill"></i></button>
                        </td>
                    </tr>
                `).join('');
            }
        }

        // Renderizar Totales
        document.getElementById('cart-subtotal').innerText = `$${this.state.subtotal.toFixed(2)}`;
        document.getElementById('cart-itbms').innerText = `$${this.state.itbms.toFixed(2)}`;
        document.getElementById('cart-total').innerText = `$${this.state.total.toFixed(2)}`;
        document.getElementById('monto-cambio').innerText = `$${this.state.cambio.toFixed(2)}`;
    }
}

// Instanciación del componente POS global
let posApp;
document.addEventListener('DOMContentLoaded', () => {
    posApp = new PosTerminalComponent();
});

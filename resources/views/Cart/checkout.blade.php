@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-4">Checkout</h2>
            
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Keranjang</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>

            {{-- Customer Information --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pembeli</h5>
                </div>
                <div class="card-body">
                    <form id="checkoutForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">No. Telepon *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Kota *</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat Lengkap *</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Catatan khusus untuk pesanan Anda..."></textarea>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Shipping Method --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Metode Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="shipping_method" id="freeShipping" value="free" checked>
                        <label class="form-check-label" for="freeShipping">
                            <strong>Pengiriman Gratis</strong><br>
                            <small class="text-muted">Estimasi 3-5 hari kerja</small>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_method" id="bankTransfer" value="bank_transfer" checked>
                                <label class="form-check-label w-100" for="bankTransfer">
                                    <i class="fas fa-university text-primary me-2"></i>
                                    <strong>Transfer Bank</strong><br>
                                    <small class="text-muted">BCA, BNI, BRI, Mandiri</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod">
                                <label class="form-check-label w-100" for="cod">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i>
                                    <strong>Bayar di Tempat (COD)</strong><br>
                                    <small class="text-muted">Bayar saat barang diterima</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    {{-- Cart Items --}}
                    @foreach($cartItems as $item)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <img src="{{ asset('storage/' . $item['product']->image) }}" 
                                 alt="{{ $item['product']->name }}"
                                 class="rounded me-3"
                                 style="width: 50px; height: 50px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 small">{{ $item['product']->name }}</h6>
                                @if(!empty($item['attributes']))
                                    <div class="small text-muted">
                                        @foreach($item['attributes'] as $attrName => $attrValue)
                                            <span class="badge bg-light text-dark me-1" style="font-size: 0.7em;">{{ $attrName }}: {{ $attrValue }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <small class="text-muted">Qty: {{ $item['quantity'] }}</small>
                            </div>
                            <span class="fw-bold small">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach

                    {{-- Pricing Summary --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal ({{ count($cartItems) }} item)</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ongkos Kirim</span>
                            <span class="text-success">Gratis</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Biaya Admin</span>
                            <span>Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total Pembayaran</strong>
                            <strong class="text-primary fs-5">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-grid gap-2">
                        <button type="submit" form="checkoutForm" class="btn btn-success btn-lg rounded-pill" id="processOrderBtn">
                            <i class="fas fa-lock me-2"></i>Proses Pesanan
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Keranjang
                        </a>
                    </div>

                    {{-- Security Badge --}}
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt text-success me-1"></i>
                            Transaksi Anda Aman & Terlindungi
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Loading Modal --}}
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5>Memproses Pesanan...</h5>
                <p class="text-muted mb-0">Mohon tunggu sebentar</p>
            </div>
        </div>
    </div>
</div>

{{-- Success Modal --}}
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                <h4 class="text-success mb-3">Pesanan Berhasil!</h4>
                <p class="text-muted mb-4">Terima kasih! Pesanan Anda telah berhasil diproses.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Lanjut Belanja</a>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Cetak Nota
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.form-check {
    transition: all 0.3s ease;
}

.form-check:hover {
    background-color: #f8f9fa;
}

.form-check-input:checked + .form-check-label {
    color: #0d6efd;
}

.sticky-top {
    position: sticky !important;
}

@media (max-width: 768px) {
    .sticky-top {
        position: relative !important;
        top: auto !important;
    }
}

.card-header {
    border-bottom: 2px solid rgba(255,255,255,0.1);
}

.border:hover {
    border-color: #0d6efd !important;
    transition: border-color 0.3s ease;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const checkoutForm = document.getElementById('checkoutForm');
    const processOrderBtn = document.getElementById('processOrderBtn');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));

    // Handle form submission
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!this.checkValidity()) {
            e.stopPropagation();
            this.classList.add('was-validated');
            return;
        }

        // Get selected payment method
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
        const shippingMethod = document.querySelector('input[name="shipping_method"]:checked')?.value;

        if (!paymentMethod || !shippingMethod) {
            alert('Silakan pilih metode pembayaran dan pengiriman.');
            return;
        }

        // Show loading
        loadingModal.show();
        processOrderBtn.disabled = true;

        // Collect form data
        const formData = new FormData(this);
        formData.append('payment_method', paymentMethod);
        formData.append('shipping_method', shippingMethod);

        // Process order
        fetch('{{ route("orders.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingModal.hide();
            processOrderBtn.disabled = false;
            
            if (data.success) {
                // Show success modal
                successModal.show();
                
                // Clear cart count
                const cartCount = document.getElementById('cartCount');
                if (cartCount) {
                    cartCount.textContent = '0';
                    cartCount.style.display = 'none';
                }
            } else {
                alert(data.message || 'Terjadi kesalahan saat memproses pesanan.');
            }
        })
        .catch(error => {
            loadingModal.hide();
            processOrderBtn.disabled = false;
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    });

    // Payment method change handler
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove active state from all payment options
            document.querySelectorAll('.form-check').forEach(check => {
                check.classList.remove('border-primary');
            });
            
            // Add active state to selected option
            this.closest('.form-check').classList.add('border-primary');
        });
    });

    // Auto-fill form if user is logged in (optional)
    @auth
        document.getElementById('name').value = '{{ auth()->user()->name ?? "" }}';
        document.getElementById('email').value = '{{ auth()->user()->email ?? "" }}';
        document.getElementById('phone').value = '{{ auth()->user()->phone ?? "" }}';
        document.getElementById('address').value = '{{ auth()->user()->address ?? "" }}';
        document.getElementById('city').value = '{{ auth()->user()->city ?? "" }}';
    @endauth

    // Input validation and formatting
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function() {
        // Remove non-numeric characters except + and -
        this.value = this.value.replace(/[^0-9+\-\s]/g, '');
    });

    // Real-time form validation
    const requiredInputs = document.querySelectorAll('input[required], textarea[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });

        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim() !== '') {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });

    // Email validation
    const emailInput = document.getElementById('email');
    emailInput.addEventListener('blur', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailRegex.test(this.value)) {
            this.classList.add('is-invalid');
            this.setCustomValidity('Format email tidak valid');
        } else {
            this.classList.remove('is-invalid');
            this.setCustomValidity('');
            if (this.value) {
                this.classList.add('is-valid');
            }
        }
    });
});
</script>
@endpush
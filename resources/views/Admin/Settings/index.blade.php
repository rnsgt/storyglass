<x-admin-layout title="Pengaturan Toko" page-title="Pengaturan Toko">
<div class="container-fluid">

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Informasi Toko -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header py-3" style="background: linear-gradient(135deg, #558b8b 0%, #84a9ac 100%); border: none;">
                <h5 class="mb-0 text-white">
                    <i class="bi bi-shop me-2"></i>Informasi Toko
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($settings['text'] ?? [] as $setting)
                        @if(in_array($setting->key, ['shop_name', 'shop_tagline', 'shop_address', 'shop_phone', 'shop_email', 'shop_whatsapp', 'shop_description']))
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-{{ 
                                    $setting->key === 'shop_name' ? 'shop' : 
                                    ($setting->key === 'shop_tagline' ? 'bookmark-star' : 
                                    ($setting->key === 'shop_address' ? 'geo-alt' : 
                                    ($setting->key === 'shop_phone' ? 'telephone' : 
                                    ($setting->key === 'shop_email' ? 'envelope' : 
                                    ($setting->key === 'shop_whatsapp' ? 'whatsapp' : 'file-text'))))) 
                                }} me-1"></i>
                                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            </label>
                            @if($setting->key === 'shop_description')
                                <textarea name="settings[{{ $setting->key }}]" class="form-control" rows="3">{{ $setting->value }}</textarea>
                            @else
                                <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="form-control">
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Pengaturan Pesanan -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header py-3" style="background: linear-gradient(135deg, #558b8b 0%, #84a9ac 100%); border: none;">
                <h5 class="mb-0 text-white">
                    <i class="bi bi-cart-check me-2"></i>Pengaturan Pesanan
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($settings['text'] ?? [] as $setting)
                        @if(in_array($setting->key, ['min_order', 'shipping_cost']))
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-{{ $setting->key === 'min_order' ? 'cash' : 'truck' }} me-1"></i>
                                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="form-control">
                            </div>
                            <small class="text-muted">
                                @if($setting->key === 'min_order')
                                    Minimal pembelian untuk dapat melakukan checkout
                                @else
                                    Biaya pengiriman standar per pesanan
                                @endif
                            </small>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Mode Maintenance -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header py-3" style="background: linear-gradient(135deg, #558b8b 0%, #84a9ac 100%); border: none;">
                <h5 class="mb-0 text-white">
                    <i class="bi bi-gear me-2"></i>Pengaturan Sistem
                </h5>
            </div>
            <div class="card-body">
                @foreach($settings['boolean'] ?? [] as $setting)
                    @if($setting->key === 'maintenance_mode')
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="settings[{{ $setting->key }}]" 
                               value="1" id="maintenance" {{ $setting->value == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="maintenance">
                            <strong>Mode Maintenance</strong>
                            <small class="d-block text-muted">
                                Aktifkan untuk menonaktifkan akses pelanggan sementara
                            </small>
                        </label>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="d-flex justify-content-end gap-2 mb-4">
            <button type="submit" class="btn btn-lg px-5" style="background: linear-gradient(135deg, #558b8b 0%, #84a9ac 100%); border: none; color: white;">
                <i class="bi bi-save me-2"></i>Simpan Pengaturan
            </button>
        </div>
    </form>

</div>

<style>
.form-label {
    color: #2d5f5f;
    margin-bottom: 0.5rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #84a9ac;
    box-shadow: 0 0 0 0.2rem rgba(132, 169, 172, 0.25);
}

.input-group-text {
    background-color: #c4e2e0;
    border-color: #84a9ac;
    color: #2d5f5f;
    font-weight: 600;
}

.form-check-input:checked {
    background-color: #558b8b;
    border-color: #558b8b;
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
</x-admin-layout>

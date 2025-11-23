@extends('layouts.main')

@section('content')
<div class="container py-5">
  <h4>Pembayaran QRIS</h4>
  <p>Scan QR berikut dengan aplikasi e‑wallet / bank Anda:</p>

  <div class="card p-4 text-center">
    <img src="{{ asset($qrcodeUrl) }}" alt="QRIS" style="max-width:320px; margin: 0 auto;">
    <p class="mt-3"><strong>Total: Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</strong></p>
    <p id="status" class="text-muted">Menunggu pembayaran...</p>
  </div>
</div>

<script>
  let pollInterval;
  
  // polling sederhana: cek status tiap 5 detik
  pollInterval = setInterval(async () => {
    try {
      const res = await fetch("{{ route('checkout.status', $order->id) }}");
      
      if (!res.ok) {
        throw new Error('Network response was not ok');
      }
      
      const json = await res.json();
      
      if (json.paid) {
        clearInterval(pollInterval);
        document.getElementById('status').textContent = 'Pembayaran berhasil! Mengalihkan...';
        document.getElementById('status').className = 'text-success';
        
        // arahkan ke halaman sukses ketika terbayar
        setTimeout(() => {
          window.location.href = "{{ route('checkout.success') }}";
        }, 1000);
      }
    } catch (error) {
      console.error('Error checking payment status:', error);
    }
  }, 5000);
</script>
@endsection
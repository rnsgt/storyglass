@extends('layouts.main')

@section('content')

<style>
    body { background-color: #cbe5e5; }
    .chat-container { max-width: 600px; margin: auto; padding: 20px; }
    .bubble-bot { background: #1b4dcc; color: white; padding: 12px 18px; border-radius: 20px; margin-bottom: 10px; width: fit-content; border-bottom-left-radius: 5px; }
    .bubble-user { background: #5aa0af; color: white; padding: 12px 18px; border-radius: 20px; margin-bottom: 10px; width: fit-content; margin-left: auto; border-bottom-right-radius: 5px; }
    .produk-card { background: white; border-radius: 20px; padding: 20px; margin-top: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }

    .quick-btn {
    background-color: white;
    border: 1px solid #1b4dcc;
    color: #1b4dcc;
    cursor: pointer;
}

.quick-btn:hover {
    background-color: #1b4dcc;
    color: white;
}

</style>

<div class="chat-container">
    <div id="chat-box"></div>

    <div class="mt-3 d-flex gap-2 flex-wrap">
        <button class="quick-btn btn btn-outline-primary rounded-pill px-3">rekomendasi kacamata wajah bulat</button>
        <button class="quick-btn btn btn-outline-primary rounded-pill px-3">rekomendasi kacamata wajah oval</button>
        <button class="quick-btn btn btn-outline-primary rounded-pill px-3">rekomendasi kacamata wajah persegi</button>
        <button class="quick-btn btn btn-outline-primary rounded-pill px-3">apa fungsi chatbot ini?</button>
    </div>

    <div class="mt-4">
        <input id="userInput" class="form-control" placeholder="Ketik pesan...">
        <button id="sendBtn" class="btn btn-primary mt-2">Kirim</button>
    </div>
</div>

<script>
document.getElementById("sendBtn").addEventListener("click", function () {
    let message = document.getElementById("userInput").value;
    if (message.trim() === "") return;

    document.getElementById("chat-box").innerHTML += `<div class="bubble-user">${message}</div>`;
    document.getElementById("userInput").value = "";

    fetch("/chatbot/send", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ message: message })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("chat-box").innerHTML += `<div class="bubble-bot">${data.reply}</div>`;

        if (data.product) {
            document.getElementById("chat-box").innerHTML += `
                <div class="produk-card">
                    <h4>${data.product.nama}</h4>
                    <img src="/image/${data.product.gambar}" style="width:150px; border-radius:10px;">
                    <p><strong>Harga</strong><br>Rp ${data.product.harga}</p>
                    <a href="{{ url('/produk') }}/${data.product.id}" class="btn btn-info text-white rounded-pill px-4">Lihat Detail</a>

                </div>
            `;
        }
    });
});

// Fungsi klik tombol cepat
document.querySelectorAll('.quick-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.getElementById("userInput").value = this.textContent;
        document.getElementById("sendBtn").click(); // otomatis kirim
    });
});

</script>

@endsection
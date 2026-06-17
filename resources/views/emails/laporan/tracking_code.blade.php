<x-mail::message>
# Halo {{ $laporan->nama }},

Terima kasih telah mengirimkan laporan kepada kami. Berikut adalah rincian tiket Anda:

**Subjek:** {{ $laporan->subject }}
**Kode Pelacakan:** **{{ $laporan->tracking_code }}**

Gunakan kode pelacakan di atas untuk memantau status laporan Anda dan melihat balasan dari admin.

<x-mail::button :url="url('/laporan/track?code=' . $laporan->tracking_code)">
Lacak Laporan
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>

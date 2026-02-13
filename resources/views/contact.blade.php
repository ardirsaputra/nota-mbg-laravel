@extends('layouts.app')

@section('title', 'Kontak - CV Mia Jaya Abadi')

@push('styles')
    <style>
        .contact-page {
            padding: 60px 24px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .contact-content {
            display: grid;
            gap: 18px;
        }

        .info-item {
            background: #f8f9fa;
            padding: 22px;
            border-radius: 12px;
            display: flex;
            gap: 20px;
            align-items: flex-start;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04)
        }

        .info-item i {
            font-size: 28px;
            color: #667eea;
            min-width: 44px
        }

        @media (min-width: 900px) {
            .contact-content {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <section id="contact" class="contact contact-page">
        <div class="container">
            <h2>Hubungi Kami</h2>

            <div class="contact-content">
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Alamat</h3>
                        <p>Jalan Metro-Gotong Royong, Dusun III, Pujodadi, Trimurjo<br>Lampung Tengah, Lampung 34173,
                            Indonesia</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>Telepon & WhatsApp</h3>
                        <p>+62 812-3456-7890<br>+62 857-1234-5678</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h3>Jam Operasional</h3>
                        <p>Senin - Sabtu: 07:00 - 17:00 WIB<br>Minggu & Hari Libur: 08:00 - 14:00 WIB</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <p>info@miajayaabadi.com</p>
                    </div>
                </div>

            </div>

            <div style="margin-top:28px; text-align:center">
                <a href="{{ route('home') }}" class="btn btn-secondary">&larr; Kembali ke Beranda</a>
            </div>
        </div>
    </section>
@endsection

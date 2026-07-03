<?php
require_once 'database.php';
include 'header.php';
?>

<style>
    :root {
        --primary-color: #4f46e5;
        --primary-hover: #4338ca;
        --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        --text-main: #1e293b;
        --text-muted: #64748b;
        --accent-color: #06b6d4;
    }

    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, var(--primary-color), var(--accent-color));
        border-radius: 10px;
    }

    .about-body {
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--text-main);
        overflow-x: hidden;
        background: radial-gradient(circle at 50% 0%, rgba(79, 70, 229, 0.05) 0%, transparent 60%);
    }

    .about-hero {
        text-align: center;
        padding: 60px 20px 40px 20px;
        background: radial-gradient(circle at top, rgba(79, 70, 229, 0.05) 0%, transparent 70%);
    }

    .about-hero h1 {
        font-size: 2.8rem;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 10px;
        letter-spacing: -1px;
    }

    .about-content-wrapper {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 20px 60px 20px;
    }

    .hero-animate {
        opacity: 0;
        transform: translateY(20px);
        animation: heroFadeIn 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes heroFadeIn {
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .reveal-section {
        opacity: 0;
        transform: translateY(50px) scale(0.97);
        filter: blur(10px);
        transition: opacity 1.1s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 1.1s cubic-bezier(0.16, 1, 0.3, 1),
                    filter 1.1s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: transform, opacity, filter;
    }

    .reveal-section.is-visible {
        opacity: 1;
        transform: translateY(0) scale(1);
        filter: blur(0);
    }

    @keyframes microFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }

    .about-card {
        background: rgba(255, 255, 255, 0.72);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 24px;
        padding: 45px;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.02);
        margin-bottom: 40px;
        transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), 
                    box-shadow 0.5s ease, 
                    border-color 0.5s ease;
    }

    .about-card:hover {
        transform: translateY(-8px) scale(1.005);
        border-color: rgba(79, 70, 229, 0.25);
        box-shadow: 0 30px 60px -15px rgba(79, 70, 229, 0.12), 
                    0 0 30px -5px rgba(6, 182, 212, 0.08);
    }

    .card-title-decor {
        margin-top: 0; 
        font-size: 1.4rem; 
        font-weight: 800; 
        border-bottom: 2px dashed #e2e8f0; 
        padding-bottom: 14px; 
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        letter-spacing: -0.5px;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 30px;
    }

    .feature-item {
        background: rgba(255, 255, 255, 0.45);
        padding: 25px 20px;
        border-radius: 18px;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.4);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .feature-item:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -10px rgba(79, 70, 229, 0.1);
    }

    .feature-item .icon {
        font-size: 2.2rem;
        margin-bottom: 12px;
        display: inline-block;
        transition: transform 0.3s ease;
    }

    .feature-item:hover .icon {
        animation: microFloat 2s ease-in-out infinite;
    }

    .feature-item h4 {
        margin: 0 0 8px 0;
        font-size: 1.05rem;
        font-weight: 700;
    }

    .feature-item p {
        margin: 0;
        font-size: 0.88rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .vision-mission-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 35px;
        line-height: 1.6;
    }

    .vm-box h5 {
        font-size: 1.15rem;
        margin: 0 0 12px 0;
        color: var(--primary-color);
        font-weight: 700;
    }

    .vm-box p {
        margin: 0;
        color: #475569;
        font-size: 0.95rem;
    }

    .vm-box ul {
        margin: 0;
        padding-left: 20px;
        color: #475569;
        font-size: 0.95rem;
    }

    .vm-box li {
        margin-bottom: 8px;
    }

    .system-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 20px;
    }

    .system-item {
        background: rgba(255, 255, 255, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 22px;
        border-radius: 18px;
        display: flex;
        gap: 16px;
        align-items: flex-start;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .system-item:hover {
        background: rgba(255, 255, 255, 0.85);
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -12px rgba(0,0,0,0.06);
    }

    .system-icon {
        font-size: 1.6rem;
        background: #eef2ff;
        padding: 10px;
        border-radius: 14px;
        color: var(--primary-color);
        line-height: 1;
        border: 1px solid rgba(79, 70, 229, 0.08);
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .system-item:hover .system-icon {
        animation: microFloat 1.8s ease-in-out infinite;
        background: #e0e7ff;
    }

    .system-text h5 {
        margin: 0 0 6px 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .system-text p {
        margin: 0;
        font-size: 0.88rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
        margin-top: 25px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 10px;
        bottom: 10px;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary-color), var(--accent-color));
    }

    .timeline-item {
        position: relative;
        margin-bottom: 35px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -30px;
        top: 4px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: white;
        border: 4px solid var(--primary-color);
        box-shadow: 0 0 12px rgba(79, 70, 229, 0.4);
        transition: all 0.3s ease;
    }

    .about-card:hover .timeline-dot {
        transform: scale(1.2);
        background: var(--accent-color);
        border-color: white;
    }

    .timeline-date {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--primary-color);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }

    .timeline-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 6px 0;
    }

    .timeline-desc {
        margin: 0;
        font-size: 0.92rem;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .testimonial-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-top: 25px;
    }

    .testi-item {
        background: rgba(255, 255, 255, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 25px;
        border-radius: 18px;
        position: relative;
        transition: all 0.3s ease;
    }

    .testi-item:hover {
        background: rgba(255, 255, 255, 0.7);
        transform: scale(1.02);
    }

    .testi-stars {
        color: #fbbf24;
        font-size: 0.85rem;
        margin-bottom: 10px;
    }

    .testi-text {
        font-style: italic;
        font-size: 0.92rem;
        color: #475569;
        line-height: 1.6;
        margin-bottom: 18px;
    }

    .testi-user {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .testi-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #eef2ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--primary-color);
        border: 1px solid rgba(79, 70, 229, 0.15);
    }

    .testi-details h6 {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .testi-details span {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .profile-section {
        display: flex;
        align-items: center;
        gap: 40px;
    }

    .profile-img-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .profile-img {
        width: 170px;
        height: 170px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .profile-img-wrapper::before {
        content: '';
        position: absolute;
        top: -5px; left: -5px; right: -5px; bottom: -5px;
        border-radius: 50%;
        background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
        z-index: -1;
        transform: rotate(0deg);
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .about-card:hover .profile-img-wrapper::before {
        transform: rotate(180deg);
    }

    .about-card:hover .profile-img {
        transform: scale(1.05);
    }

    .profile-info h3 {
        font-size: 1.6rem;
        font-weight: 800;
        margin: 0 0 6px 0;
        color: var(--text-main);
        letter-spacing: -0.5px;
    }

    .profile-info .badge-role {
        display: inline-block;
        padding: 5px 14px;
        background: #eef2ff;
        color: var(--primary-color);
        font-size: 0.78rem;
        font-weight: 700;
        border-radius: 20px;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid rgba(79, 70, 229, 0.1);
    }

    .profile-info p {
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--text-muted);
        margin: 0 0 22px 0;
    }

    .social-links {
        display: flex;
        gap: 12px;
    }

    .social-btn {
        padding: 8px 18px;
        background: rgba(241, 245, 249, 0.8);
        color: var(--text-main);
        text-decoration: none;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid rgba(0,0,0,0.03);
    }

    .social-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
    }

    @media (max-width: 768px) {
        .about-hero h1 { font-size: 2.2rem; }
        .profile-section { flex-direction: column; text-align: center; gap: 25px; }
        .features-grid, .vision-mission-grid, .system-grid, .testimonial-grid { grid-template-columns: 1fr; }
        .about-card { padding: 35px 20px; }
        .social-links { justify-content: center; }
    }
</style>

<div class="about-body">
    <div class="about-hero hero-animate">
        <h1>Tentang <span class="highlight">Espresso & Co.</span></h1>
        <p style="color: var(--text-muted); font-size: 1.15rem; max-width: 650px; margin: 0 auto; line-height: 1.6;">
            Lebih dari sekadar cangkir kopi, kami merajut cerita, kenyamanan, dan inovasi teknologi digital di setiap seduhan.
        </p>
    </div>

    <div class="about-content-wrapper">
        
        <div class="about-card reveal-section">
            <h3 class="card-title-decor">🌱 Filosofi Kami</h3>
            <p style="line-height: 1.7; color: #475569; font-size: 0.98rem; margin-bottom: 20px;">
                Didirikan dengan komitmen untuk menyajikan biji kopi pilihan terbaik langsung dari petani lokal, <strong>Espresso & Co.</strong> hadir sebagai ruang hangat bagi siapa saja. Baik kamu yang ingin produktif menyelesaikan tugas pemrograman, merancang ide desain kreatif, atau sekadar berbincang santai menikmati senja.
            </p>
            
            <div class="features-grid">
                <div class="feature-item">
                    <span class="icon">✨</span>
                    <h4>Kualitas Premium</h4>
                    <p>Bahan pilihan kualitas terbaik demi kepuasan cita rasa otentik.</p>
                </div>
                <div class="feature-item">
                    <span class="icon">⚡</span>
                    <h4>Pelayanan Kilat</h4>
                    <p>Sistem order digital real-time terhubung langsung ke dapur.</p>
                </div>
                <div class="feature-item">
                    <span class="icon">🍃</span>
                    <h4>Suasana Hangat</h4>
                    <p>Desain tempat yang nyaman dan ramah untuk produktivitas.</p>
                </div>
            </div>
        </div>

        <div class="about-card reveal-section">
            <h3 class="card-title-decor">🎯 Arah & Tujuan</h3>
            <div class="vision-mission-grid">
                <div class="vm-box">
                    <h5>Visi Kami</h5>
                    <p>
                        Menjadi pelopor kedai kopi modern terdepan yang mengintegrasikan kesempurnaan rasa rasa kopi lokal dengan kenyamanan ekosistem digital untuk generasi kreatif.
                    </p>
                </div>
                <div class="vm-box">
                    <h5>Misi Kami</h5>
                    <ul>
                        <li>Menghadirkan menu kopi dan makanan kualitas terbaik dengan harga yang bersahabat bagi mahasiswa.</li>
                        <li>Mengembangkan platform transaksi yang instan, transparan, dan bebas ribet bagi pelanggan.</li>
                        <li>Mendukung ekosistem petani lokal dengan pasokan komoditas berkelanjutan.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="about-card reveal-section">
            <h3 class="card-title-decor">⚙️ Arsitektur Sistem Modern</h3>
            <p style="color: #475569; font-size: 0.95rem; line-height: 1.6; margin: 0 0 15px 0;">
                Aplikasi ini digerakkan oleh kombinasi teknologi <strong>PHP Native</strong> yang efisien dan basis data <strong>MySQL</strong> yang solid. Kami merancang arsitektur sistem yang mengutamakan kecepatan interaksi data serta kenyamanan penuh bagi pengguna.
            </p>
            
            <div class="system-grid">
                <div class="system-item">
                    <div class="system-icon">🚀</div>
                    <div class="system-text">
                        <h5>Pemrosesan Data Kilat</h5>
                        <p>Kueri database yang teroptimasi memastikan alur dari pemesanan menu hingga cetak nota berjalan mulus tanpa hambatan.</p>
                    </div>
                </div>
                <div class="system-item">
                    <div class="system-icon">📉</div>
                    <div class="system-text">
                        <h5>Sinkronisasi Stok Otomatis</h5>
                        <p>Setiap transaksi yang berhasil diselesaikan akan memotong stok bahan menu secara real-time untuk mencegah kesalahan sistem order.</p>
                    </div>
                </div>
                <div class="system-item">
                    <div class="system-icon">🔐</div>
                    <div class="system-text">
                        <h5>Enkripsi & Integritas Data</h5>
                        <p>Menjamin validitas nomor invoice transaksi unik serta otentikasi login kasir yang aman guna menghindari redudansi data.</p>
                    </div>
                </div>
                <div class="system-item">
                    <div class="system-icon">📱</div>
                    <div class="system-text">
                        <h5>Desain UI/UX Adaptif</h5>
                        <p>Tampilan antarmuka berbasis CSS modern yang fleksibel, nyaman, dan presisi baik diakses melalui smartphone maupun perangkat laptop.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="about-card reveal-section">
            <h3 class="card-title-decor">🚀 Perjalanan Kami</h3>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-date">20 Juni 2026</div>
                    <h4 class="timeline-title">Konseptual & Site Observation</h4>
                    <p class="timeline-desc">Memulai riset pasar, observasi mendalam mengenai kebutuhan ruang produktif yang ramah mahasiswa, serta merancang pondasi awal manajemen kafe.</p>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-date">21-25 Juni 2026</div>
                    <h4 class="timeline-title">Perancangan Sistem Awal</h4>
                    <p class="timeline-desc">Sistem jual beli pada website Espresso & Co. dirancang secara matang, mengedepankan estetika modern yang cocok dengan tren generasi masa kini.</p>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-date">1 Juli 2026</div>
                    <h4 class="timeline-title">Peluncuran Sistem Digital Terintegrasi</h4>
                    <p class="timeline-desc">Aplikasi web pemesanan kafe berbasis PHP dan MySQL resmi mengudara untuk memberikan pengalaman pemesanan yang responsif dan efisien.</p>
                </div>
            </div>
        </div>

        <div class="about-card reveal-section">
            <h3 class="card-title-decor">💬 Apa Kata Mereka?</h3>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 5px;">Pendapat jujur dari pengunjung setia yang menjadikan Espresso & Co. sebagai markas produktivitas mereka.</p>
            
            <div class="testimonial-grid">
                <div class="testi-item">
                    <div class="testi-stars">⭐⭐⭐⭐⭐</div>
                    <div class="testi-text">"Kopinya enak banget, terutama Ice Caramel Latte-nya pas! Ditambah lagi mesennya gampang tinggal klik lewat web, gak perlu ngantre panjang di kasir."</div>
                    <div class="testi-user">
                        <div class="testi-avatar">R</div>
                        <div class="testi-details">
                            <h6>Rafi Ardiansyah</h6>
                            <span>Mahasiswa / Pelanggan Tetap</span>
                        </div>
                    </div>
                </div>
                <div class="testi-item">
                    <div class="testi-stars">⭐⭐⭐⭐⭐</div>
                    <div class="testi-text">"Tempat andalan buat nugas kuliah atau nyelesaiin projek freelance. Wi-Fi kencang, suasananya tenang, dan Ayam Geprek + Nasinya juara kalau lapar!"</div>
                    <div class="testi-user">
                        <div class="testi-avatar">F</div>
                        <div class="testi-details">
                            <h6>Fadzilla U.</h6>
                            <span>UI/UX Designer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="about-card reveal-section">
            <h3 class="card-title-decor">💻 Di Balik Sistem</h3>
            <div class="profile-section">
                <div class="profile-img-wrapper">
                    <img src="Owner.png" 
                         alt="Khoerul Fadli" 
                         class="profile-img">
                </div>
                
                <div class="profile-info">
                    <span class="badge-role">Founder & Lead Developer</span>
                    <h3>Khoerul Fadli</h3>
                    <p>
                        Halo! Saya adalah kreator di balik ekosistem digital Espresso & Co. Saya adalah seorang mahasiswa LP3I Karawang prodi Application Software Engineeroing,Menggabungkan hobi di bidang <i>UI/UX Design</i> serta ketertarikan mendalam pada teknologi <i>Web Development</i> untuk menciptakan pengalaman pemesanan menu kafe yang interaktif, instan, dan ramah pengguna.
                    </p>
                    
                   <div class="social-links">
                    <a href="https://www.instagram.com/erulphlviii" target="_blank" class="social-btn">📸 Instagram</a>
                </div>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 50px;" class="reveal-section">
            <a href="order.php" style="background: var(--primary-color); color: white; text-decoration: none; padding: 14px 36px; border-radius: 12px; font-weight: 700; font-size: 1rem; box-shadow: 0 4px 18px rgba(79, 70, 229, 0.35); transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); display: inline-block;"
               onmouseover="this.style.background='#4338ca'; this.style.transform='translateY(-5px) scale(1.05)'; this.style.boxShadow='0 12px 28px rgba(79, 70, 229, 0.45)';"
               onmouseout="this.style.background='#4f46e5'; this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 4px 18px rgba(79, 70, 229, 0.35)';">
               Mulai Pesan Kopi Sekarang ☕
            </a>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sections = document.querySelectorAll('.reveal-section');
        
        const observerOptions = {
            root: null,
            threshold: 0.12, 
            rootMargin: "0px 0px -20px 0px"
        };

        const sectionObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        sections.forEach(section => {
            sectionObserver.observe(section);
        });
    });
</script>

<?php include 'footer.php'; ?>
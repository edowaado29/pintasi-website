<div
    style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 24px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
    <h2 style="color: #333;">Halo,</h2>

    <p style="font-size: 16px; color: #555; line-height: 1.6;">
        Kami menerima permintaan untuk mereset kata sandi akun Anda. Jika Anda tidak merasa melakukan permintaan ini,
        silakan abaikan email ini dengan aman.
    </p>

    <p style="font-size: 16px; color: #555; line-height: 1.6;">
        Untuk mengatur ulang kata sandi Anda, silakan klik tombol di bawah ini:
    </p>

    <div style="text-align: center; margin: 24px 0;">
        <a href="{{ route('validasi_forgot_password', ['token' => $token]) }}"
            style="padding: 12px 24px; background-color: #007bff; color: white; text-decoration: none; font-weight: bold; border-radius: 6px; display: inline-block;">
            Atur Ulang Kata Sandi
        </a>
    </div>

    <p style="font-size: 14px; color: #999; line-height: 1.6;">
        Tautan ini berlaku selama <strong>24 jam</strong> demi keamanan akun Anda. Jika Anda memiliki pertanyaan atau
        membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi tim dukungan kami.
    </p>

    <p style="font-size: 16px; color: #555; margin-top: 40px;">
        Hormat kami,<br>
        <strong>Tim Pintasi</strong>
    </p>
</div>

/**
 * Checkout page interactivity
 * - Voucher validation & application
 * - Real-time total calculation
 * - Payment method fee adjustment
 * - Terms agreement check on submit
 */
document.addEventListener('DOMContentLoaded', function () {
    const checkoutForm = document.getElementById('checkoutForm');
    if (!checkoutForm) return;

    const currentSubtotal = parseFloat(checkoutForm.dataset.subtotal) || 0;
    let discount = 0;
    let voucherId = null;

    const applyVoucherBtn = document.getElementById('applyVoucherBtn');
    const voucherCodeInput = document.getElementById('voucher_code');
    const voucherMessageEl = document.getElementById('voucherMessage');
    const voucherIdInput = document.getElementById('voucher_id');
    const diskonInput = document.getElementById('diskon');
    const totalAmountEl = document.getElementById('totalAmount');
    const grandTotalInput = document.getElementById('grand_total');
    const payButton = document.getElementById('payButton');
    const agreeTermsCheck = document.getElementById('agree_terms');

    /**
     * Tampilkan pesan voucher di bawah input.
     */
    function showVoucherMessage(msg, type) {
        if (!voucherMessageEl) return;
        const cls = type === 'success'
            ? 'bg-[#34c759]/10 text-[#34c759] border-[#34c759]/20'
            : 'bg-[#d70015]/10 text-[#d70015] border-[#d70015]/20';
        voucherMessageEl.innerHTML =
            '<div class="rounded-xl border ' + cls + ' flex items-center gap-2 p-2 text-xs"><span>' + msg + '</span></div>';
    }

    /**
     * Reset state voucher.
     */
    function resetVoucher() {
        voucherId = null;
        discount = 0;
        if (voucherIdInput) voucherIdInput.value = '';
        if (diskonInput) diskonInput.value = 0;
        if (voucherCodeInput) voucherCodeInput.value = '';
        calculateTotal();
    }

    /**
     * Hitung total dengan mempertimbangkan diskon dan fee payment method.
     */
    function calculateTotal() {
        let total = currentSubtotal - discount;
        const selected = document.querySelector('input[name="payment_method_id"]:checked');
        if (selected) {
            const pct = parseFloat(selected.dataset.feePercentage || 0) || 0;
            const flat = parseFloat(selected.dataset.feeFlat || 0) || 0;
            if (pct > 0) total += total * pct / 100;
            if (flat > 0) total += flat;
        }
        if (totalAmountEl) {
            totalAmountEl.textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
        }
        if (grandTotalInput) {
            grandTotalInput.value = Math.round(total);
        }
    }

    // Event: klik tombol terapkan voucher
    if (applyVoucherBtn && voucherCodeInput) {
        applyVoucherBtn.addEventListener('click', function () {
            const code = voucherCodeInput.value.trim();
            if (!code) {
                showVoucherMessage('Masukkan kode voucher', 'danger');
                return;
            }

            const params = new URLSearchParams();
            const tokenInput = document.querySelector('input[name="_token"]');
            if (tokenInput) params.append('_token', tokenInput.value);
            params.append('voucher_code', code);
            params.append('subtotal', currentSubtotal);

            fetch(checkoutForm.dataset.validateUrl, {
                method: 'POST',
                body: params,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                .then(function (r) { return r.json(); })
                .then(function (r) {
                    if (r.success) {
                        showVoucherMessage(r.message, 'success');
                        voucherId = r.voucher.id;
                        discount = r.voucher.diskon;
                        if (voucherIdInput) voucherIdInput.value = voucherId;
                        if (diskonInput) diskonInput.value = discount;
                        calculateTotal();
                    } else {
                        showVoucherMessage(r.message, 'danger');
                        resetVoucher();
                    }
                })
                .catch(function () {
                    showVoucherMessage('Terjadi kesalahan. Coba lagi.', 'danger');
                    resetVoucher();
                });
        });
    }

    // Event: ganti payment method → hitung ulang total
    document.querySelectorAll('input[name="payment_method_id"]').forEach(function (m) {
        m.addEventListener('change', calculateTotal);
    });

    // Event: submit form → cek persetujuan syarat
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            if (agreeTermsCheck && !agreeTermsCheck.checked) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Persetujuan Diperlukan',
                        text: 'Harap setujui Syarat & Ketentuan',
                        confirmButtonColor: '#1d1d1f'
                    });
                } else {
                    if (confirm('Setujui Syarat & Ketentuan?')) {
                        agreeTermsCheck.checked = true;
                        checkoutForm.submit();
                    }
                }
                return;
            }

            // Tampilkan loading state
            if (payButton) {
                payButton.disabled = true;
                payButton.innerHTML =
                    '<span class="gooey-loader" style="--gooey-dot:7px;margin-right:8px"><i></i><i></i><i></i></span>Memproses...';
            }
        });
    }

    // Hitung total awal
    calculateTotal();
});

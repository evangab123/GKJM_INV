document.addEventListener("DOMContentLoaded", function() {
    const hargaPembelianInput = document.getElementById('harga_pembelian');
    const tahunPembelianInput = document.getElementById('tahun_pembelian');
    const nilaiEkonomisInput = document.getElementById('nilai_ekonomis_barang');

    function calculateNilaiEkonomis() {
        const hargaPembelian = parseFloat(hargaPembelianInput.value) || 0;
        const tahunPembelian = parseInt(tahunPembelianInput.value) || new Date().getFullYear();

        console.log(`Harga Pembelian: ${hargaPembelian}, Tahun Pembelian: ${tahunPembelian}`);

        // Calculate Nilai Ekonomis
        const nilaiEkonomis = hargaPembelian * tahunPembelian;

        console.log(`Nilai Ekonomis: ${nilaiEkonomis}`);

        nilaiEkonomisInput.value = nilaiEkonomis;
    }

    hargaPembelianInput.addEventListener('input', calculateNilaiEkonomis);
    tahunPembelianInput.addEventListener('input', calculateNilaiEkonomis);
});

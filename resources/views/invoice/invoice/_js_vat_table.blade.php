<script>
    document.addEventListener('DOMContentLoaded', function () {
        handleVatInput()
        calculateTotals()
        document.getElementById('vat').addEventListener('input', handleVatInput)
        document.querySelectorAll('.vatRadio').forEach(radio => {
            radio.addEventListener('change', handleVatInput);
        })
    })

    const handleVatInput = () => {
        const vatInput = document.getElementById('vat')
        const vatValue = parseFloat(vatInput.value)

        const tableBody = document.querySelector('#charges tbody')
        const rows = tableBody.querySelectorAll('tr')
        rows.forEach((row, index) => {
            const usdInput = row.querySelector(`#usd_${index}`)
            const egpInput = row.querySelector(`#egp_${index}`)
            const usdValue = parseFloat(usdInput.value)
            const egpValue = parseFloat(egpInput.value)

            const addVatRadio = row.querySelector(`#item_${index}_enabled_yes`)
            let usdValueWithVat = usdValue
            let egpValueWithVat = egpValue
            if (addVatRadio && addVatRadio.checked) {
                usdValueWithVat = usdValue + (usdValue * (vatValue / 100))
                egpValueWithVat = egpValue + (egpValue * (vatValue / 100))
            }
            row.querySelector(`#usd_vat_${index}`).value = usdValueWithVat.toFixed(2)
            row.querySelector(`#egp_vat_${index}`).value = egpValueWithVat.toFixed(2)
        });
        calculateTotals()
    }

    function calculateTotals() {
        let totEgp = 0;
        let totUsd = 0;
        $('#charges tbody tr').each(function() {
            var egpAmount = $(this).find('input[name$="[egp_vat]"]').val();
            var usdAmount = $(this).find('input[name$="[usd_vat]"]').val();
            totEgp = totEgp + parseFloat(egpAmount);
            totUsd = totUsd + parseFloat(usdAmount);
        });
        $('input[id="total_egp"]').val(totEgp);
        $('input[id="total_usd"]').val(totUsd);
    }
</script>
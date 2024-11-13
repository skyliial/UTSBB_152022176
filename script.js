async function fetchData() {
    try {
        const response = await fetch('http://localhost/uts_iot/get_data.php');
        const data = await response.json();

        // Tampilkan Data Ringkasan
        document.getElementById('suhuMax').textContent = data.suhuMax;
        document.getElementById('suhuMin').textContent = data.suhuMin;
        document.getElementById('suhuRata').textContent = data.suhuRata;

        // Tampilkan Data Detail dalam Tabel
        const detailsTableBody = document.getElementById('nilai_suhu_max_humid_max');
        data.nilai_suhu_max_humid_max.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.idx}</td>
                <td>${item.suhu}</td>
                <td>${item.humid}</td>
                <td>${item.kecerahan}</td>
                <td>${item.timestamp}</td>
            `;
            detailsTableBody.appendChild(row);
        });

        // Tampilkan Month-Year Max Data
        const monthYearContainer = document.getElementById('month_year_max');
        data.month_year_max.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item.month_year;
            monthYearContainer.appendChild(li);
        });
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

// Panggil fetchData saat halaman dimuat
fetchData();

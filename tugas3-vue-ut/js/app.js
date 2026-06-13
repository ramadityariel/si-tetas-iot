import { apiService } from './services/api.js';
import StatusBadge from './components/status-badge.js';
import StockTable from './components/stock-table.js';

const { createApp, ref, onMounted } = Vue;

const app = createApp({
  template: `
    <div class="container">
      <div class="header">
        <h1>📦 Sistem Informasi Stok Bahan Ajar UT</h1>
        <p>Kelola daftar stok, harga, dan distribusi bahan ajar secara efisien.</p>
      </div>
      <stock-table :initial-data="bahanAjarData" @data-changed="saveData"></stock-table>
    </div>
  `,
  setup() {
    const bahanAjarData = ref([]);

    onMounted(async () => {
      bahanAjarData.value = await apiService.fetchData();
    });

    const saveData = (newData) => {
      bahanAjarData.value = newData;
      apiService.saveData(newData);
    };

    return {
      bahanAjarData,
      saveData
    };
  }
});

app.component('status-badge', StatusBadge);
app.component('stock-table', StockTable);

app.mount('#app');

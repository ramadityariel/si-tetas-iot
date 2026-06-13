export default {
  name: 'StockTable',
  props: ['initialData'],
  emits: ['data-changed'],
  setup(props, { emit }) {
    const { ref, computed, watch } = Vue;
    const data = ref([]);
    
    // Sinkronisasi data awal
    watch(() => props.initialData, (newVal) => {
      data.value = [...newVal];
    }, { deep: true, immediate: true });

    // State untuk filter
    const filterUpbjj = ref('');
    const filterKategori = ref('');
    const filterReorder = ref(false);

    // State untuk sorting
    const sortKey = ref('');
    const sortOrder = ref(1);

    // State untuk edit inline
    const editingId = ref(null);
    const editForm = ref({});

    // State untuk tambah data baru
    const newForm = ref({
      kode: '', judul: '', kategori: '', upbjj: '', lokasiRak: '', harga: 0, qty: 0, safety: 0, catatanHTML: ''
    });

    // Pilihan Filter Berdasarkan Data
    const upbjjOptions = computed(() => {
      const upbjjs = new Set(data.value.map(item => item.upbjj));
      return Array.from(upbjjs).sort();
    });

    const kategoriOptions = computed(() => {
      if (!filterUpbjj.value) return [];
      const kats = new Set(data.value.filter(item => item.upbjj === filterUpbjj.value).map(item => item.kategori));
      return Array.from(kats).sort();
    });

    // Reset Filters
    const resetFilters = () => {
      filterUpbjj.value = '';
      filterKategori.value = '';
      filterReorder.value = false;
      sortKey.value = '';
    };

    // Computed Property untuk Filter dan Sort data (Sesuai kebutuhan menghindari recompute yg tdk perlu)
    const filteredAndSortedData = computed(() => {
      let result = data.value;

      // Filter UPBJJ
      if (filterUpbjj.value) {
        result = result.filter(item => item.upbjj === filterUpbjj.value);
      }
      
      // Filter Kategori (Dependent Options)
      if (filterUpbjj.value && filterKategori.value) {
        result = result.filter(item => item.kategori === filterKategori.value);
      }

      // Filter Reorder
      if (filterReorder.value) {
        result = result.filter(item => item.qty < item.safety || item.qty === 0);
      }

      // Sort data
      if (sortKey.value) {
        result = [...result].sort((a, b) => {
          let valA = a[sortKey.value];
          let valB = b[sortKey.value];
          if (typeof valA === 'string') valA = valA.toLowerCase();
          if (typeof valB === 'string') valB = valB.toLowerCase();
          
          if (valA < valB) return -1 * sortOrder.value;
          if (valA > valB) return 1 * sortOrder.value;
          return 0;
        });
      }

      return result;
    });

    const setSort = (key) => {
      if (sortKey.value === key) {
        sortOrder.value = sortOrder.value * -1;
      } else {
        sortKey.value = key;
        sortOrder.value = 1;
      }
    };

    // Formatting Text
    const formatCurrency = (value) => {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
    };

    const formatNumber = (value) => {
      return value + ' buah';
    };

    // CRUD Actions
    const startEdit = (item) => {
      editingId.value = item.id;
      editForm.value = { ...item };
    };

    const cancelEdit = () => {
      editingId.value = null;
      editForm.value = {};
    };

    const saveEdit = () => {
      if (!editForm.value.kode || !editForm.value.judul) {
        alert("Kode dan Judul tidak boleh kosong!");
        return;
      }
      const index = data.value.findIndex(item => item.id === editingId.value);
      if (index !== -1) {
        data.value[index] = { ...editForm.value };
        emit('data-changed', data.value);
      }
      cancelEdit();
    };

    const createData = () => {
      if (!newForm.value.kode || !newForm.value.judul) {
        alert("Kode dan Judul tidak boleh kosong!");
        return;
      }
      const newId = data.value.length > 0 ? Math.max(...data.value.map(item => item.id)) + 1 : 1;
      data.value.push({ ...newForm.value, id: newId });
      emit('data-changed', data.value);
      
      newForm.value = {
        kode: '', judul: '', kategori: '', upbjj: '', lokasiRak: '', harga: 0, qty: 0, safety: 0, catatanHTML: ''
      };
    };

    const deleteData = (id) => {
      if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
        data.value = data.value.filter(item => item.id !== id);
        emit('data-changed', data.value);
      }
    };

    return {
      filterUpbjj, filterKategori, filterReorder, sortKey, sortOrder,
      upbjjOptions, kategoriOptions, resetFilters,
      filteredAndSortedData, setSort,
      formatCurrency, formatNumber,
      editingId, editForm, startEdit, cancelEdit, saveEdit,
      newForm, createData, deleteData
    };
  },
  template: `
    <div>
      <div class="filter-section">
        <div class="filter-item">
          <label>UT-Daerah</label>
          <select v-model="filterUpbjj" @change="filterKategori = ''">
            <option value="">Semua Daerah</option>
            <option v-for="opt in upbjjOptions" :key="opt" :value="opt">{{ opt }}</option>
          </select>
        </div>
        
        <div class="filter-item" v-if="filterUpbjj">
          <label>Kategori Mata Kuliah</label>
          <select v-model="filterKategori">
            <option value="">Semua Kategori</option>
            <option v-for="opt in kategoriOptions" :key="opt" :value="opt">{{ opt }}</option>
          </select>
        </div>

        <div class="filter-item">
          <label>Filter Stok (Re-order)</label>
          <label class="checkbox-label">
            <input type="checkbox" v-model="filterReorder">
            Stok Menipis / Kosong
          </label>
        </div>

        <div class="filter-item">
          <label>&nbsp;</label>
          <button @click="resetFilters" class="btn-secondary">Reset Filter</button>
        </div>
      </div>

      <div style="overflow-x: auto;">
        <table>
          <thead>
            <tr>
              <th>Kode MK</th>
              <th @click="setSort('judul')" class="sortable">Judul MK <span v-if="sortKey === 'judul'">{{ sortOrder === 1 ? '▲' : '▼' }}</span></th>
              <th>Kategori</th>
              <th>UT-Daerah</th>
              <th>Lokasi Rak</th>
              <th @click="setSort('harga')" class="sortable">Harga <span v-if="sortKey === 'harga'">{{ sortOrder === 1 ? '▲' : '▼' }}</span></th>
              <th @click="setSort('qty')" class="sortable">Qty <span v-if="sortKey === 'qty'">{{ sortOrder === 1 ? '▲' : '▼' }}</span></th>
              <th>Safety Stok</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in filteredAndSortedData" :key="item.id">
              <template v-if="editingId === item.id">
                <td><input v-model="editForm.kode" @keyup.enter="saveEdit" required></td>
                <td><input v-model="editForm.judul" @keyup.enter="saveEdit" required></td>
                <td><input v-model="editForm.kategori" @keyup.enter="saveEdit"></td>
                <td><input v-model="editForm.upbjj" @keyup.enter="saveEdit"></td>
                <td><input v-model="editForm.lokasiRak" @keyup.enter="saveEdit"></td>
                <td><input type="number" v-model.number="editForm.harga" @keyup.enter="saveEdit"></td>
                <td><input type="number" v-model.number="editForm.qty" @keyup.enter="saveEdit"></td>
                <td><input type="number" v-model.number="editForm.safety" @keyup.enter="saveEdit"></td>
                <td><input v-model="editForm.catatanHTML" placeholder="Catatan..." @keyup.enter="saveEdit"></td>
                <td class="action-btns">
                  <button @click="saveEdit" class="btn-success">Simpan</button>
                  <button @click="cancelEdit" class="btn-secondary">Batal</button>
                </td>
              </template>
              <template v-else>
                <td>{{ item.kode }}</td>
                <td>{{ item.judul }}</td>
                <td>{{ item.kategori }}</td>
                <td>{{ item.upbjj }}</td>
                <td>{{ item.lokasiRak }}</td>
                <td>{{ formatCurrency(item.harga) }}</td>
                <td>{{ formatNumber(item.qty) }}</td>
                <td>{{ formatNumber(item.safety) }}</td>
                <td>
                  <status-badge :qty="item.qty" :safety="item.safety" :catatan="item.catatanHTML"></status-badge>
                </td>
                <td class="action-btns">
                  <button @click="startEdit(item)">Edit</button>
                  <button @click="deleteData(item.id)" class="btn-danger">Hapus</button>
                </td>
              </template>
            </tr>
            <tr class="add-form-row">
              <td><input v-model="newForm.kode" placeholder="Kode" @keyup.enter="createData"></td>
              <td><input v-model="newForm.judul" placeholder="Judul" @keyup.enter="createData"></td>
              <td><input v-model="newForm.kategori" placeholder="Kategori" @keyup.enter="createData"></td>
              <td><input v-model="newForm.upbjj" placeholder="UT-Daerah" @keyup.enter="createData"></td>
              <td><input v-model="newForm.lokasiRak" placeholder="Lokasi Rak" @keyup.enter="createData"></td>
              <td><input type="number" v-model.number="newForm.harga" placeholder="Harga" @keyup.enter="createData"></td>
              <td><input type="number" v-model.number="newForm.qty" placeholder="Qty" @keyup.enter="createData"></td>
              <td><input type="number" v-model.number="newForm.safety" placeholder="Safety" @keyup.enter="createData"></td>
              <td><input v-model="newForm.catatanHTML" placeholder="Catatan..." @keyup.enter="createData"></td>
              <td>
                <button @click="createData" class="btn-success">Tambah</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <p class="help-text">💡 Tekan 'Enter' pada kolom input untuk menyimpan atau memperbarui data secara instan.</p>
    </div>
  `
}

const STORAGE_KEY = 'BAHAN_AJAR_DATA_VUE';

export const apiService = {
  async fetchData() {
    let data = localStorage.getItem(STORAGE_KEY);
    if (!data) {
      try {
        const response = await fetch('./data/dataBahanAjar.json');
        const json = await response.json();
        localStorage.setItem(STORAGE_KEY, JSON.stringify(json));
        return json;
      } catch (error) {
        console.error("Error fetching data:", error);
        return [];
      }
    }
    return JSON.parse(data);
  },
  saveData(data) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
  }
};

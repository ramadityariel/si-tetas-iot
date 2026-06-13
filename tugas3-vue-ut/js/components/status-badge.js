export default {
  name: 'StatusBadge',
  props: {
    qty: {
      type: Number,
      required: true
    },
    safety: {
      type: Number,
      required: true
    },
    catatan: {
      type: String,
      default: ''
    }
  },
  computed: {
    statusInfo() {
      if (this.qty === 0) {
        return { text: 'Kosong', class: 'status-kosong', icon: '⛔' };
      } else if (this.qty < this.safety) {
        return { text: 'Menipis', class: 'status-menipis', icon: '⚠️' };
      } else {
        return { text: 'Aman', class: 'status-aman', icon: '✅' };
      }
    }
  },
  template: `
    <div class="status-badge" :class="statusInfo.class">
      <span>{{ statusInfo.icon }} {{ statusInfo.text }}</span>
      <div v-if="catatan" class="tooltip">{{ catatan }}</div>
    </div>
  `
}

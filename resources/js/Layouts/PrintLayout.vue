<script setup>
defineProps({
  title: { type: String, default: 'Dokumen' },
})

function doPrint() {
  window.print()
}
</script>

<template>
  <!-- Screen: preview with toolbar -->
  <div class="print-shell">
    <!-- Toolbar (hidden when printing) -->
    <div class="no-print print-toolbar">
      <button class="print-btn" @click="doPrint">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
          <path fill-rule="evenodd" d="M5 2.75A2.75 2.75 0 0 1 7.75 0h4.5A2.75 2.75 0 0 1 15 2.75V4.5h.25A3.75 3.75 0 0 1 19 8.25v4A3.75 3.75 0 0 1 15.25 16H15v1.25A2.75 2.75 0 0 1 12.25 20h-4.5A2.75 2.75 0 0 1 5 17.25V16h-.25A3.75 3.75 0 0 1 1 12.25v-4A3.75 3.75 0 0 1 4.75 4.5H5V2.75Zm2.75-1.25A1.25 1.25 0 0 0 6.5 2.75V4.5h7V2.75A1.25 1.25 0 0 0 12.25 1.5h-4.5ZM5 14.5H4.75A2.25 2.25 0 0 1 2.5 12.25v-4A2.25 2.25 0 0 1 4.75 6h10.5A2.25 2.25 0 0 1 17.5 8.25v4A2.25 2.25 0 0 1 15.25 14.5H15V13a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1v1.5Zm1.5 0v2.75A1.25 1.25 0 0 0 7.75 18.5h4.5A1.25 1.25 0 0 0 13.5 17.25V14.5h-7Z" clip-rule="evenodd" />
        </svg>
        Cetak / Print
      </button>
      <a href="javascript:history.back()" class="back-btn">← Kembali</a>
    </div>

    <!-- Document paper preview -->
    <div class="print-paper">
      <slot />
    </div>
  </div>
</template>

<style>
/* ===== Screen styles ===== */
.print-shell {
  min-height: 100vh;
  background: #e5e7eb;
  padding: 24px 16px 48px;
}

.print-toolbar {
  display: flex;
  align-items: center;
  gap: 12px;
  max-width: 210mm;
  margin: 0 auto 16px;
}

.print-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 18px;
  background: #16a34a;
  color: #fff;
  font-size: 14px;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}
.print-btn:hover { background: #15803d; }

.back-btn {
  font-size: 14px;
  color: #4b5563;
  text-decoration: none;
}
.back-btn:hover { color: #111; }

.print-paper {
  background: #fff;
  max-width: 210mm;
  margin: 0 auto;
  padding: 20mm 20mm 24mm;
  box-shadow: 0 4px 24px rgba(0,0,0,.15);
  font-family: 'Times New Roman', Times, serif;
  font-size: 12pt;
  color: #111;
}

/* ===== Print media ===== */
@media print {
  @page {
    size: A4;
    margin: 20mm 20mm 24mm;
  }

  body {
    background: #fff !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }

  .no-print {
    display: none !important;
  }

  .print-shell {
    background: none;
    padding: 0;
  }

  .print-paper {
    box-shadow: none;
    padding: 0;
    max-width: 100%;
    margin: 0;
  }
}
</style>

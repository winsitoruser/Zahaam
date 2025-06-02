#!/bin/bash
set -e

# Fungsi untuk menunggu PostgreSQL siap
wait_for_postgres() {
  echo "Waiting for PostgreSQL..."
  
  until PGPASSWORD="zahaampassword" psql -h "db" -U "zahaam" -d "stock_prediction" -c '\q'; do
    echo "PostgreSQL belum siap - menunggu..."
    sleep 2
  done
  
  echo "PostgreSQL siap!"
}

# Jalankan migrasi database
run_migrations() {
  echo "Menjalankan migrasi database..."
  python pg_migration.py
  echo "Migrasi selesai."
}

# Jalankan fetch data jika diperlukan
fetch_data_if_needed() {
  echo "Memeriksa apakah perlu mengambil data saham..."
  
  # Daftar interval yang didukung
  INTERVALS=("1d" "1h" "15m" "5m")
  
  for INTERVAL in "${INTERVALS[@]}"; do
    # Hitung jumlah data di tabel stock_prices untuk interval tertentu
    RECORDS=$(PGPASSWORD="zahaampassword" psql -h "db" -U "zahaam" -d "stock_prediction" -t -c "SELECT COUNT(*) FROM stock_prices WHERE interval='$INTERVAL';")
    
    if [ -z "$RECORDS" ] || [ "$RECORDS" -lt 100 ]; then
      echo "Data saham untuk interval $INTERVAL kurang dari 100 record, memulai pengambilan data..."
      # Menggunakan script fetch_historical_data_improved.py dengan parameter interval
      python fetch_historical_data_improved.py --interval $INTERVAL
      echo "Pengambilan data untuk interval $INTERVAL selesai."
    else
      echo "Data saham untuk interval $INTERVAL sudah cukup (${RECORDS} record). Tidak perlu mengambil ulang."
    fi
  done
}

# Main execution
main() {
  # Tunggu PostgreSQL
  wait_for_postgres
  
  # Jalankan migrasi
  run_migrations
  
  # Cek dan ambil data jika perlu
  fetch_data_if_needed
  
  # Eksekusi perintah yang diberikan
  exec "$@"
}

# Run main function
main "$@"

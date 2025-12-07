import http from 'k6/http';
import { check, sleep } from 'k6';
import { parseHTML } from 'k6/html';

// 1. LOAD DATA DARI JSON FILE
// Kita naik satu folder (../) lalu masuk ke folder data
const userData = JSON.parse(open('../../performance-testing/data/user.json'));

// --- KONFIGURASI ---
const BASE_URL = 'https://reportaction.dbsnetwork.my.id'; 

export const options = {
  stages: [
    { duration: '10s', target: 50 }, 
    { duration: '30s', target: 50 },
    { duration: '1s', target: 0 },
  ],
  thresholds: {
    http_req_failed: ['rate<0.05'], 
    http_req_duration: ['p(95)<3000'], 
  },
};

export default function () {
  // ======================================================
  // LANGKAH 1: BUKA HALAMAN LOGIN (GET)
  // ======================================================
  
  const loginPageRes = http.get(`${BASE_URL}/login`);

  check(loginPageRes, {
    'Halaman login terbuka (200)': (r) => r.status === 200,
  });

  // Parsing CSRF Token
  const doc = parseHTML(loginPageRes.body);
  const csrfToken = doc.find('input[name="_token"]').val();

  if (!csrfToken) {
      console.error("❌ Gagal mengambil CSRF Token!");
      return;
  }

  // ======================================================
  // LANGKAH 2: KIRIM DATA LOGIN (POST)
  // ======================================================

  const loginRes = http.post(`${BASE_URL}/login`, {
    _token: csrfToken, 
    username: userData.username, // Mengambil dari JSON
    password: userData.password, // Mengambil dari JSON
  });

  // Debugging jika gagal
  if (loginRes.status !== 200) {
      console.log(`❌ Login Gagal. Status: ${loginRes.status}`);
  }

  check(loginRes, {
    'Login berhasil (Masuk Dashboard)': (r) => r.status === 200,
  });

  sleep(1);
}
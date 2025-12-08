import http from 'k6/http';
import { check, sleep } from 'k6';
import { parseHTML } from 'k6/html';
import { SharedArray } from 'k6/data';

const BASE_URL = 'https://reportaction.dbsnetwork.my.id';
const users = new SharedArray('users', () => JSON.parse(open('../../performance-testing/data/userReport.json')));

export const options = {
  stages: [
    { duration: '10s', target: 3 },
    { duration: '20s', target: 5 },
    { duration: '10s', target: 0 },
  ],
  insecureSkipTLSVerify: true,
  noConnectionReuse: false,
};

export default function () {
  const jar = http.cookieJar();
  const user = users[__VU % users.length];

  // 1️⃣ LOGIN
  const loginPageRes = http.get(`${BASE_URL}/login`, { jar });
  const loginDoc = parseHTML(loginPageRes.body);
  const csrfToken = loginDoc.find('input[name="_token"]').first().attr('value');

  const loginRes = http.post(
    `${BASE_URL}/login`,
    `_token=${csrfToken}&username=${user.username}&password=${user.password}`,
    {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest',
        'Origin': BASE_URL,
        'Referer': `${BASE_URL}/login`,
      },
      redirects: 1,
      jar,
    }
  );

  check(loginRes, { '✅ Login berhasil (200/302)': (r) => r.status === 200 || r.status === 302 });
  sleep(0.5);

  // 2️⃣ GET create_ajax
  const createPageRes = http.get(`${BASE_URL}/laporan/create_ajax`, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    jar,
  });

  check(createPageRes, { '✅ Form laporan terbuka': (r) => r.status === 200 });

  const createDoc = parseHTML(createPageRes.body);
  let newCsrfToken = createDoc.find('input[name="_token"]').first().attr('value');
  if (!newCsrfToken) {
    const xsrfCookie = jar.cookiesForURL(BASE_URL)['XSRF-TOKEN'];
    newCsrfToken = xsrfCookie ? decodeURIComponent(xsrfCookie[0]) : null;
  }

  if (!newCsrfToken) {
    console.error('❌ Tidak ada CSRF token di form laporan');
    return;
  }

  // 3️⃣ Ambil ID dinamis dari AJAX endpoint
  let gedung_id = 1;
  let lantai_id = null;
  let ruang_id = null;
  let sarana_id = null;

  // Ambil lantai
  const lantaiRes = http.get(`${BASE_URL}/laporan/ajax/lantai/${gedung_id}`, { jar });
  if (lantaiRes.status === 200) {
    try {
      const lantaiData = JSON.parse(lantaiRes.body);
      if (lantaiData.length > 0) lantai_id = lantaiData[0].lantai_id;
    } catch {}
  }

  // Ambil ruang
  if (lantai_id) {
    const ruangRes = http.get(`${BASE_URL}/laporan/ajax/ruang-by-lantai/${lantai_id}`, { jar });
    if (ruangRes.status === 200) {
      try {
        const ruangData = JSON.parse(ruangRes.body);
        if (ruangData.length > 0) ruang_id = ruangData[0].ruang_id;
      } catch {}
    }
  }

  // Ambil sarana
  if (ruang_id) {
    const saranaRes = http.get(`${BASE_URL}/laporan/ajax/sarana-by-ruang/${ruang_id}`, { jar });
    if (saranaRes.status === 200) {
      try {
        const saranaData = JSON.parse(saranaRes.body);
        if (saranaData.length > 0) sarana_id = saranaData[0].sarana_id;
      } catch {}
    }
  }

  if (!lantai_id || !ruang_id || !sarana_id) {
    console.error('❌ Data referensi tidak ditemukan (lantai/ruang/sarana)');
    return;
  }

  // 4️⃣ Kirim laporan
  const laporanPayload =
    `_token=${newCsrfToken}` +
    `&gedung_id=${gedung_id}` +
    `&lantai_id=${lantai_id}` +
    `&ruang_id=${ruang_id}` +
    `&sarana_id=${sarana_id}` +
    `&laporan_judul=Laporan+K6+VU${__VU}-ITER${__ITER}` +
    `&tingkat_kerusakan=sedang&tingkat_urgensi=tinggi&dampak_kerusakan=sedang`;

  const laporanRes = http.post(`${BASE_URL}/laporan/store_ajax`, laporanPayload, {
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-CSRF-TOKEN': newCsrfToken,
      'X-Requested-With': 'XMLHttpRequest',
    },
    jar,
  });

  const laporanOK = check(laporanRes, { '✅ Laporan berhasil dibuat': (r) => r.status === 200 });
  if (!laporanOK) {
    console.error(`❌ Gagal membuat laporan (status ${laporanRes.status})`);
    console.error(laporanRes.body);
  } else {
    console.log(`✅ ${user.username} berhasil membuat laporan (VU ${__VU})`);
  }

  sleep(1);
}

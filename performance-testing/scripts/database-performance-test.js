import http from 'k6/http';
import { check, sleep } from 'k6';
import { parseHTML } from 'k6/html';
import { SharedArray } from 'k6/data';

const BASE_URL = 'http://localhost:8000';
const users = new SharedArray('users', () => JSON.parse(open('../data/user.json')));

export const options = {
  scenarios: {
    database_load: {
      executor: 'ramping-vus',
      startVUs: 1,
      stages: [
        { duration: '30s', target: 5 },
        { duration: '30s', target: 10 },
        { duration: '30s', target: 0 },
      ],
    },
  },
  thresholds: {
    http_req_failed: ['rate<0.05'],
    http_req_duration: ['p(95)<3000'],
  },
};

export default function () {
  const jar = http.cookieJar();
  const user = users[__VU % users.length];

  // Login first
  if (!performLogin(jar, user)) return;
  
  // Test database-heavy operations
  testLaporanListPerformance(jar, user);
  testUserListPerformance(jar, user);
  testSearchPerformance(jar, user);
  testPaginationPerformance(jar, user);
  
  // Logout
  performLogout(jar);
  
  sleep(1);
}

function performLogin(jar, user) {
  const loginPageRes = http.get(`${BASE_URL}/login`, { jar });
  
  if (loginPageRes.status !== 200) return false;

  const loginDoc = parseHTML(loginPageRes.body);
  const csrfToken = loginDoc.find('input[name="_token"]').first().attr('value');

  const loginRes = http.post(
    `${BASE_URL}/login`,
    `_token=${csrfToken}&username=${user.username}&password=${user.password}`,
    {
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      jar,
    }
  );

  return loginRes.status === 302;
}

function testLaporanListPerformance(jar, user) {
  console.log('📋 Testing Laporan List Database Performance...');
  
  // Only test for users who can access laporan
  if (!['admin', 'sarpras'].includes(user.username) && !user.username.includes('teknisi')) {
    return;
  }
  
  const startTime = Date.now();
  
  const laporanRes = http.get(`${BASE_URL}/laporan`, {
    headers: {
      'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    },
    jar,
  });

  const queryTime = Date.now() - startTime;

  check(laporanRes, {
    '✅ Laporan list loads': (r) => r.status === 200,
    '✅ Laporan list has data': (r) => r.body.includes('laporan') || r.body.includes('table'),
    '✅ Laporan query time acceptable': () => queryTime < 2000,
  });

  if (laporanRes.status === 200) {
    console.log(`📊 Laporan list query time: ${queryTime}ms`);
  }
}

function testUserListPerformance(jar, user) {
  console.log('👥 Testing User List Database Performance...');
  
  // Only admin can access user list
  if (user.username !== 'admin') return;
  
  const startTime = Date.now();
  
  const userRes = http.get(`${BASE_URL}/user`, {
    headers: {
      'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    },
    jar,
  });

  const queryTime = Date.now() - startTime;

  check(userRes, {
    '✅ User list loads': (r) => r.status === 200,
    '✅ User list has data': (r) => r.body.includes('user') || r.body.includes('table'),
    '✅ User query time acceptable': () => queryTime < 1500,
  });

  if (userRes.status === 200) {
    console.log(`📊 User list query time: ${queryTime}ms`);
  }
}

function testSearchPerformance(jar, user) {
  console.log('🔍 Testing Search Database Performance...');
  
  // Test search on laporan if user has access
  if (!['admin', 'sarpras'].includes(user.username) && !user.username.includes('teknisi')) {
    return;
  }
  
  const searchTerms = ['test', 'kerusakan', 'perbaikan', 'maintenance'];
  const searchTerm = searchTerms[Math.floor(Math.random() * searchTerms.length)];
  
  const startTime = Date.now();
  
  const searchRes = http.get(`${BASE_URL}/laporan?search=${searchTerm}`, {
    headers: {
      'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    },
    jar,
  });

  const searchTime = Date.now() - startTime;

  check(searchRes, {
    '✅ Search executes': (r) => r.status === 200,
    '✅ Search returns results': (r) => r.body.length > 1000,
    '✅ Search time acceptable': () => searchTime < 2500,
  });

  if (searchRes.status === 200) {
    console.log(`📊 Search query time: ${searchTime}ms for term "${searchTerm}"`);
  }
}

function testPaginationPerformance(jar, user) {
  console.log('📄 Testing Pagination Database Performance...');
  
  // Test pagination on accessible pages
  const pages = [];
  
  if (['admin', 'sarpras'].includes(user.username) || user.username.includes('teknisi')) {
    pages.push('/laporan');
  }
  
  if (user.username === 'admin') {
    pages.push('/user');
  }
  
  pages.forEach(page => {
    // Test first page
    const startTime1 = Date.now();
    const page1Res = http.get(`${BASE_URL}${page}?page=1`, { jar });
    const page1Time = Date.now() - startTime1;

    check(page1Res, {
      [`✅ ${page} page 1 loads`]: (r) => r.status === 200,
      [`✅ ${page} page 1 time acceptable`]: () => page1Time < 2000,
    });

    // Test second page
    const startTime2 = Date.now();
    const page2Res = http.get(`${BASE_URL}${page}?page=2`, { jar });
    const page2Time = Date.now() - startTime2;

    check(page2Res, {
      [`✅ ${page} page 2 loads`]: (r) => r.status === 200,
      [`✅ ${page} page 2 time acceptable`]: () => page2Time < 2000,
    });

    console.log(`📊 ${page} pagination - Page 1: ${page1Time}ms, Page 2: ${page2Time}ms`);
    
    sleep(0.2);
  });
}

function performLogout(jar) {
  http.post(`${BASE_URL}/logout`, {}, { jar });
}

// Heavy database load test
export function heavyDatabaseLoad() {
  const jar = http.cookieJar();
  const user = users.find(u => u.username === 'admin') || users[0];
  
  if (!performLogin(jar, user)) return;
  
  console.log('🔥 Running Heavy Database Load Test...');
  
  // Rapid database queries
  for (let i = 0; i < 10; i++) {
    const operations = [
      () => http.get(`${BASE_URL}/laporan`, { jar }),
      () => http.get(`${BASE_URL}/user`, { jar }),
      () => http.get(`${BASE_URL}/profile`, { jar }),
      () => http.get(`${BASE_URL}/laporan?search=test`, { jar }),
    ];
    
    const operation = operations[Math.floor(Math.random() * operations.length)];
    const startTime = Date.now();
    const res = operation();
    const duration = Date.now() - startTime;
    
    check(res, {
      [`✅ Heavy load operation ${i + 1}`]: (r) => r.status === 200,
      [`✅ Heavy load time ${i + 1} acceptable`]: () => duration < 5000,
    });
    
    console.log(`📊 Heavy load operation ${i + 1}: ${duration}ms`);
    
    sleep(0.1);
  }
  
  performLogout(jar);
}

// Concurrent database access test
export function concurrentDatabaseAccess() {
  const jar = http.cookieJar();
  const user = users[0];
  
  if (!performLogin(jar, user)) return;
  
  console.log('🔄 Testing Concurrent Database Access...');
  
  // Prepare concurrent requests
  const requests = [
    ['GET', `${BASE_URL}/profile`, null, { jar }],
    ['GET', `${BASE_URL}/`, null, { jar }],
    ['GET', `${BASE_URL}/profile`, null, { jar }],
  ];
  
  // Execute concurrent requests
  const startTime = Date.now();
  const responses = http.batch(requests);
  const totalTime = Date.now() - startTime;
  
  responses.forEach((response, index) => {
    check(response, {
      [`✅ Concurrent DB request ${index + 1}`]: (r) => r.status === 200,
    });
  });
  
  console.log(`📊 Concurrent database access total time: ${totalTime}ms`);
  
  performLogout(jar);
}
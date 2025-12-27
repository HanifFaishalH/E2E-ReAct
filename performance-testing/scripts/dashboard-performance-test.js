import http from 'k6/http';
import { check, sleep } from 'k6';
import { parseHTML } from 'k6/html';
import { SharedArray } from 'k6/data';

const BASE_URL = 'http://localhost:8000';
const users = new SharedArray('users', () => JSON.parse(open('../data/user.json')));

export const options = {
  scenarios: {
    dashboard_load: {
      executor: 'ramping-vus',
      startVUs: 1,
      stages: [
        { duration: '30s', target: 15 },
        { duration: '1m', target: 25 },
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

  // Login
  if (!performLogin(jar, user)) return;
  
  // Test dashboard based on user role
  testDashboardPerformance(jar, user);
  
  // Test dashboard components
  testDashboardComponents(jar, user);
  
  // Logout
  performLogout(jar);
  
  sleep(1);
}

function performLogin(jar, user) {
  const loginPageRes = http.get(`${BASE_URL}/login`, { jar });
  
  if (loginPageRes.status !== 200) {
    console.error('❌ Cannot access login page');
    return false;
  }

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

  return check(loginRes, {
    '✅ Login successful': (r) => r.status === 302 || r.status === 200,
  });
}

function testDashboardPerformance(jar, user) {
  console.log(`📊 Testing Dashboard Performance for ${user.username}...`);
  
  const startTime = Date.now();
  
  const dashboardRes = http.get(`${BASE_URL}/`, {
    headers: {
      'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
      'Cache-Control': 'no-cache',
    },
    jar,
  });

  const loadTime = Date.now() - startTime;
  const sizeKB = dashboardRes.body.length / 1024;

  const dashboardChecks = check(dashboardRes, {
    '✅ Dashboard loads successfully': (r) => r.status === 200,
    '✅ Dashboard contains navigation': (r) => r.body.includes('nav') || r.body.includes('menu'),
    '✅ Dashboard has content': (r) => r.body.length > 1000,
    '✅ Dashboard size reasonable': (r) => (r.body.length / 1024) < 500, // < 500KB
  });

  if (dashboardChecks) {
    console.log(`📈 Dashboard load time: ${loadTime}ms, Size: ${sizeKB.toFixed(2)}KB`);
    
    // Test role-specific content
    testRoleSpecificContent(dashboardRes, user);
  }
}

function testRoleSpecificContent(dashboardRes, user) {
  const body = dashboardRes.body.toLowerCase();
  
  // Check for role-specific elements
  if (user.username === 'admin' || user.username === 'sarpras') {
    check(dashboardRes, {
      '✅ Admin dashboard has management features': (r) => 
        r.body.includes('laporan') || r.body.includes('manage') || r.body.includes('kelola'),
    });
  }
  
  if (user.username.includes('teknisi')) {
    check(dashboardRes, {
      '✅ Teknisi dashboard has work features': (r) => 
        r.body.includes('kelola') || r.body.includes('laporan') || r.body.includes('tugas'),
    });
  }
  
  if (user.username === 'mahasiswa' || user.username === 'dosen') {
    check(dashboardRes, {
      '✅ User dashboard has basic features': (r) => 
        r.body.includes('profile') || r.body.includes('laporan'),
    });
  }
}

function testDashboardComponents(jar, user) {
  console.log('🧩 Testing Dashboard Components...');
  
  // Test common dashboard endpoints
  const endpoints = [
    { path: '/profile', name: 'Profile' },
  ];
  
  // Add role-specific endpoints
  if (user.username === 'admin' || user.username === 'sarpras') {
    endpoints.push(
      { path: '/laporan', name: 'Laporan Management' },
      { path: '/user', name: 'User Management' }
    );
  }
  
  if (user.username.includes('teknisi')) {
    endpoints.push(
      { path: '/teknisi/kelola-laporan', name: 'Kelola Laporan' }
    );
  }

  endpoints.forEach(endpoint => {
    const startTime = Date.now();
    
    const res = http.get(`${BASE_URL}${endpoint.path}`, {
      headers: {
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
      },
      jar,
    });

    const loadTime = Date.now() - startTime;

    check(res, {
      [`✅ ${endpoint.name} accessible`]: (r) => r.status === 200,
      [`✅ ${endpoint.name} loads quickly`]: (r) => r.timings.duration < 2000,
    });

    if (res.status === 200) {
      console.log(`📊 ${endpoint.name} load time: ${loadTime}ms`);
    }
    
    sleep(0.2);
  });
}

function performLogout(jar) {
  const logoutRes = http.post(`${BASE_URL}/logout`, {}, { jar });
  
  check(logoutRes, {
    '✅ Logout successful': (r) => r.status === 302,
  });
}

// Heavy load test for dashboard
export function dashboardHeavyLoad() {
  const jar = http.cookieJar();
  const user = users[0];
  
  if (!performLogin(jar, user)) return;
  
  // Simulate heavy dashboard usage
  for (let i = 0; i < 10; i++) {
    const res = http.get(`${BASE_URL}/`, { jar });
    
    check(res, {
      [`✅ Heavy load iteration ${i + 1}`]: (r) => r.status === 200,
      [`✅ Heavy load response time ${i + 1}`]: (r) => r.timings.duration < 5000,
    });
    
    sleep(0.1);
  }
  
  performLogout(jar);
}

// Concurrent dashboard access test
export function concurrentDashboardAccess() {
  const requests = [];
  
  // Prepare multiple requests
  for (let i = 0; i < 5; i++) {
    requests.push(['GET', `${BASE_URL}/`, null, {}]);
  }
  
  // Execute concurrent requests
  const responses = http.batch(requests);
  
  responses.forEach((response, index) => {
    check(response, {
      [`✅ Concurrent request ${index + 1} successful`]: (r) => r.status === 200 || r.status === 302,
      [`✅ Concurrent request ${index + 1} response time`]: (r) => r.timings.duration < 3000,
    });
  });
}
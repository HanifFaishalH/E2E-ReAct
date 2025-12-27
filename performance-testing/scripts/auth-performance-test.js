import http from 'k6/http';
import { check, sleep } from 'k6';
import { parseHTML } from 'k6/html';
import { SharedArray } from 'k6/data';

const BASE_URL = 'http://localhost:8000';
const users = new SharedArray('users', () => JSON.parse(open('../data/user.json')));

export const options = {
  scenarios: {
    auth_performance: {
      executor: 'constant-vus',
      vus: 20,
      duration: '2m',
    },
    auth_stress: {
      executor: 'ramping-vus',
      startVUs: 1,
      stages: [
        { duration: '30s', target: 50 },
        { duration: '1m', target: 100 },
        { duration: '30s', target: 0 },
      ],
    },
  },
  thresholds: {
    http_req_failed: ['rate<0.05'],
    http_req_duration: ['p(95)<2000'],
  },
};

export default function () {
  const user = users[__VU % users.length];
  
  // Test complete authentication flow
  testAuthenticationFlow(user);
  
  // Test session management
  testSessionManagement(user);
  
  // Test authentication security
  testAuthSecurity();
  
  sleep(1);
}

function testAuthenticationFlow(user) {
  console.log(`🔐 Testing Authentication Flow for ${user.username}...`);
  
  const jar = http.cookieJar();
  
  // 1. Test login page load
  const loginPageStartTime = Date.now();
  const loginPageRes = http.get(`${BASE_URL}/login`, { jar });
  const loginPageLoadTime = Date.now() - loginPageStartTime;

  check(loginPageRes, {
    '✅ Login page loads': (r) => r.status === 200,
    '✅ Login page has form': (r) => r.body.includes('username') && r.body.includes('password'),
    '✅ Login page has CSRF': (r) => r.body.includes('_token'),
    '✅ Login page load time < 500ms': () => loginPageLoadTime < 500,
  });

  // Extract CSRF token
  const loginDoc = parseHTML(loginPageRes.body);
  const csrfToken = loginDoc.find('input[name="_token"]').first().attr('value');

  if (!csrfToken) {
    console.error('❌ No CSRF token found');
    return false;
  }

  // 2. Test login process
  const loginStartTime = Date.now();
  const loginRes = http.post(
    `${BASE_URL}/login`,
    `_token=${csrfToken}&username=${user.username}&password=${user.password}`,
    {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Origin': BASE_URL,
        'Referer': `${BASE_URL}/login`,
      },
      jar,
    }
  );
  const loginDuration = Date.now() - loginStartTime;

  const loginSuccess = check(loginRes, {
    '✅ Login successful': (r) => r.status === 302 || r.status === 200,
    '✅ Login redirects properly': (r) => r.status === 302,
    '✅ Login duration acceptable': () => loginDuration < 1500,
  });

  if (!loginSuccess) {
    console.error(`❌ Login failed for ${user.username}`);
    return false;
  }

  console.log(`📊 Login duration: ${loginDuration}ms`);

  // 3. Test authenticated access
  const dashboardRes = http.get(`${BASE_URL}/`, { jar });
  
  check(dashboardRes, {
    '✅ Dashboard accessible after login': (r) => r.status === 200,
    '✅ Dashboard shows user content': (r) => !r.body.includes('login'),
  });

  // 4. Test logout process
  const logoutStartTime = Date.now();
  const logoutRes = http.post(`${BASE_URL}/logout`, {}, {
    headers: {
      'Origin': BASE_URL,
      'Referer': `${BASE_URL}/`,
    },
    jar,
  });
  const logoutDuration = Date.now() - logoutStartTime;

  check(logoutRes, {
    '✅ Logout successful': (r) => r.status === 302,
    '✅ Logout duration acceptable': () => logoutDuration < 500,
  });

  console.log(`📊 Logout duration: ${logoutDuration}ms`);

  // 5. Verify logout worked
  const afterLogoutRes = http.get(`${BASE_URL}/profile`, { jar });
  
  check(afterLogoutRes, {
    '✅ Protected page inaccessible after logout': (r) => r.status === 302, // Should redirect to login
  });

  return true;
}

function testSessionManagement(user) {
  console.log('🍪 Testing Session Management...');
  
  const jar = http.cookieJar();
  
  // Login
  const loginPageRes = http.get(`${BASE_URL}/login`, { jar });
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

  if (loginRes.status !== 302) return;

  // Test session persistence
  const profileRes1 = http.get(`${BASE_URL}/profile`, { jar });
  const profileRes2 = http.get(`${BASE_URL}/profile`, { jar });

  check(profileRes1, {
    '✅ Session persists - first request': (r) => r.status === 200,
  });

  check(profileRes2, {
    '✅ Session persists - second request': (r) => r.status === 200,
  });

  // Test session cookies
  const cookies = jar.cookiesForURL(BASE_URL);
  
  check(cookies, {
    '✅ Session cookie exists': (c) => Object.keys(c).some(key => key.includes('session') || key.includes('laravel')),
  });

  // Logout
  http.post(`${BASE_URL}/logout`, {}, { jar });
}

function testAuthSecurity() {
  console.log('🛡️ Testing Authentication Security...');
  
  // Test invalid credentials
  const loginPageRes = http.get(`${BASE_URL}/login`);
  const loginDoc = parseHTML(loginPageRes.body);
  const csrfToken = loginDoc.find('input[name="_token"]').first().attr('value');

  const invalidLoginRes = http.post(
    `${BASE_URL}/login`,
    `_token=${csrfToken}&username=invalid&password=invalid`,
    {
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    }
  );

  check(invalidLoginRes, {
    '✅ Invalid login rejected': (r) => r.status !== 200 || r.body.includes('error') || r.body.includes('invalid'),
  });

  // Test CSRF protection
  const noCsrfLoginRes = http.post(
    `${BASE_URL}/login`,
    'username=admin&password=admin',
    {
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    }
  );

  check(noCsrfLoginRes, {
    '✅ CSRF protection active': (r) => r.status === 419 || r.status === 403 || r.body.includes('CSRF'),
  });

  // Test direct access to protected routes
  const directAccessRes = http.get(`${BASE_URL}/profile`);
  
  check(directAccessRes, {
    '✅ Protected route requires auth': (r) => r.status === 302, // Should redirect to login
  });
}

// Rapid authentication test
export function rapidAuthTest() {
  const user = users[0];
  
  for (let i = 0; i < 5; i++) {
    console.log(`🔄 Rapid auth test iteration ${i + 1}`);
    
    const jar = http.cookieJar();
    
    // Quick login
    const loginPageRes = http.get(`${BASE_URL}/login`, { jar });
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

    // Quick logout
    if (loginRes.status === 302) {
      http.post(`${BASE_URL}/logout`, {}, { jar });
    }

    check(loginRes, {
      [`✅ Rapid auth ${i + 1} successful`]: (r) => r.status === 302,
    });
    
    sleep(0.1);
  }
}

// Concurrent login test
export function concurrentLoginTest() {
  const requests = [];
  
  // Get CSRF token first
  const loginPageRes = http.get(`${BASE_URL}/login`);
  const loginDoc = parseHTML(loginPageRes.body);
  const csrfToken = loginDoc.find('input[name="_token"]').first().attr('value');
  
  // Prepare concurrent login requests
  for (let i = 0; i < 3; i++) {
    const user = users[i % users.length];
    requests.push([
      'POST',
      `${BASE_URL}/login`,
      `_token=${csrfToken}&username=${user.username}&password=${user.password}`,
      {
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      }
    ]);
  }
  
  // Execute concurrent requests
  const responses = http.batch(requests);
  
  responses.forEach((response, index) => {
    check(response, {
      [`✅ Concurrent login ${index + 1} handled`]: (r) => r.status === 302 || r.status === 422, // Success or validation error
    });
  });
}
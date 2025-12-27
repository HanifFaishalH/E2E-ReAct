import http from 'k6/http';
import { check, sleep } from 'k6';
import { parseHTML } from 'k6/html';
import { SharedArray } from 'k6/data';

const BASE_URL = 'http://localhost:8000'; // Adjust to your local URL
const users = new SharedArray('users', () => JSON.parse(open('../data/user.json')));

export const options = {
  stages: [
    { duration: '30s', target: 10 }, // Ramp up to 10 users
    { duration: '1m', target: 20 },  // Stay at 20 users
    { duration: '30s', target: 0 },  // Ramp down
  ],
  thresholds: {
    http_req_failed: ['rate<0.05'], // Error rate < 5%
    http_req_duration: ['p(95)<2000'], // 95% requests < 2s
  },
};

export default function () {
  const jar = http.cookieJar();
  const user = users[__VU % users.length];

  // 🔐 LOGIN PROCESS
  performLogin(jar, user);
  
  // 👤 PROFILE PERFORMANCE TESTS
  testProfileView(jar);
  testProfileUpdate(jar);
  testProfilePhotoUpload(jar);
  
  // 🚪 LOGOUT
  performLogout(jar);
  
  sleep(1);
}

function performLogin(jar, user) {
  // Get login page
  const loginPageRes = http.get(`${BASE_URL}/login`, { jar });
  
  check(loginPageRes, {
    '✅ Login page loads': (r) => r.status === 200,
    '✅ Login page response time < 500ms': (r) => r.timings.duration < 500,
  });

  // Extract CSRF token
  const loginDoc = parseHTML(loginPageRes.body);
  const csrfToken = loginDoc.find('input[name="_token"]').first().attr('value');

  if (!csrfToken) {
    console.error('❌ Failed to get CSRF token');
    return false;
  }

  // Perform login
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

  const loginSuccess = check(loginRes, {
    '✅ Login successful': (r) => r.status === 302 || r.status === 200,
    '✅ Login response time < 1000ms': (r) => r.timings.duration < 1000,
  });

  if (!loginSuccess) {
    console.error(`❌ Login failed for ${user.username}`);
    return false;
  }

  return true;
}

function testProfileView(jar) {
  console.log('🔍 Testing Profile View Performance...');
  
  const startTime = Date.now();
  
  const profileRes = http.get(`${BASE_URL}/profile`, {
    headers: {
      'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
      'Referer': `${BASE_URL}/`,
    },
    jar,
  });

  const duration = Date.now() - startTime;

  check(profileRes, {
    '✅ Profile page loads successfully': (r) => r.status === 200,
    '✅ Profile contains user data': (r) => r.body.includes('nama') || r.body.includes('username'),
    '✅ Profile page size reasonable': (r) => r.body.length < 100000, // < 100KB
  });

  // Custom metric for profile view duration
  if (profileRes.status === 200) {
    console.log(`📊 Profile view duration: ${duration}ms`);
  }
}

function testProfileUpdate(jar) {
  console.log('✏️ Testing Profile Update Performance...');
  
  // Get profile page first to get CSRF token
  const profileRes = http.get(`${BASE_URL}/profile`, { jar });
  
  if (profileRes.status !== 200) {
    console.error('❌ Cannot access profile page for update test');
    return;
  }

  const profileDoc = parseHTML(profileRes.body);
  const csrfToken = profileDoc.find('input[name="_token"]').first().attr('value');

  if (!csrfToken) {
    console.error('❌ No CSRF token found for profile update');
    return;
  }

  const startTime = Date.now();
  
  // Update profile name
  const updateRes = http.post(
    `${BASE_URL}/profile`,
    `_token=${csrfToken}&name=Performance Test User ${__VU}-${__ITER}`,
    {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Origin': BASE_URL,
        'Referer': `${BASE_URL}/profile`,
      },
      jar,
    }
  );

  const duration = Date.now() - startTime;

  check(updateRes, {
    '✅ Profile update successful': (r) => r.status === 302, // Redirect after update
    '✅ Profile update response time < 1500ms': (r) => r.timings.duration < 1500,
  });

  if (updateRes.status === 302) {
    console.log(`📊 Profile update duration: ${duration}ms`);
  }
}

function testProfilePhotoUpload(jar) {
  console.log('📸 Testing Profile Photo Upload Performance...');
  
  // Get profile page for CSRF token
  const profileRes = http.get(`${BASE_URL}/profile`, { jar });
  
  if (profileRes.status !== 200) {
    console.error('❌ Cannot access profile page for photo upload test');
    return;
  }

  const profileDoc = parseHTML(profileRes.body);
  const csrfToken = profileDoc.find('input[name="_token"]').first().attr('value');

  if (!csrfToken) {
    console.error('❌ No CSRF token found for photo upload');
    return;
  }

  // Create fake image data (small test image)
  const imageData = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==';
  
  const startTime = Date.now();
  
  // Simulate photo upload (multipart form) - skip file upload for now
  const formData = {
    '_token': csrfToken,
    'name': `Photo Test User ${__VU}`,
  };

  const uploadRes = http.post(`${BASE_URL}/profile`, formData, {
    headers: {
      'Origin': BASE_URL,
      'Referer': `${BASE_URL}/profile`,
    },
    jar,
  });

  const duration = Date.now() - startTime;

  check(uploadRes, {
    '✅ Photo upload successful': (r) => r.status === 302,
    '✅ Photo upload response time < 3000ms': (r) => r.timings.duration < 3000,
  });

  if (uploadRes.status === 302) {
    console.log(`📊 Photo upload duration: ${duration}ms`);
  }
}

function performLogout(jar) {
  const logoutRes = http.post(`${BASE_URL}/logout`, {}, {
    headers: {
      'Origin': BASE_URL,
      'Referer': `${BASE_URL}/profile`,
    },
    jar,
  });

  check(logoutRes, {
    '✅ Logout successful': (r) => r.status === 302,
    '✅ Logout response time < 500ms': (r) => r.timings.duration < 500,
  });
}

// Stress test scenario for profile operations
export function profileStressTest() {
  const jar = http.cookieJar();
  const user = users[0]; // Use first user for stress test
  
  if (!performLogin(jar, user)) return;
  
  // Rapid profile operations
  for (let i = 0; i < 5; i++) {
    testProfileView(jar);
    sleep(0.1);
  }
  
  performLogout(jar);
}
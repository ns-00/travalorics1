import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// ── Request tracking with improved UX ──────────────────────
let activeRequests = 0;
let loadingTimer = null;
let loadingLayerIndex = null;

function showLoading() {
  if (loadingLayerIndex === null) {
    loadingLayerIndex = layer.load(2, {
      shade: [0.15, '#fff'],
      content: '<div style="margin-top: 20px; color: #6F4E37; font-weight: 600; font-size: 14px;">جاري التحميل...</div>'
    });
  }
}

function hideLoading() {
  if (loadingLayerIndex !== null) {
    layer.close(loadingLayerIndex);
    loadingLayerIndex = null;
  }
  // Close any loading layer opened by legacy request handlers as well.
  // This prevents a stale shade from keeping the panel unclickable.
  if (window.layer && typeof window.layer.closeAll === 'function') {
    window.layer.closeAll('loading');
  }
}

window.panelLoading = {
  show: showLoading,
  hide: hideLoading,
  reset: () => {
    activeRequests = 0;
    if (loadingTimer) clearTimeout(loadingTimer);
    hideLoading();
  }
};

window.addEventListener('pageshow', () => window.panelLoading.reset());
window.addEventListener('pagehide', () => window.panelLoading.reset());

axios.interceptors.request.use(
  config => {
    activeRequests++;
    if (loadingTimer) clearTimeout(loadingTimer);
    loadingTimer = setTimeout(() => {
      if (activeRequests > 0) showLoading();
    }, 300);
    return config;
  },
  error => {
    activeRequests = Math.max(0, activeRequests - 1);
    if (activeRequests === 0) {
      if (loadingTimer) clearTimeout(loadingTimer);
      hideLoading();
    }
    return Promise.reject(error);
  }
);

axios.interceptors.response.use(
  response => {
    activeRequests = Math.max(0, activeRequests - 1);
    if (activeRequests === 0) {
      if (loadingTimer) clearTimeout(loadingTimer);
      hideLoading();
    }
    return response.data;
  },
  error => {
    activeRequests = Math.max(0, activeRequests - 1);
    if (activeRequests === 0) {
      if (loadingTimer) clearTimeout(loadingTimer);
      hideLoading();
    }
    const msg = error.response?.data?.message || error.response?.data?.error || error.message || 'حدث خطأ غير متوقع';
    if (window.layer && typeof window.layer.msg === 'function') {
      if (error.response?.status !== 422) {
        window.layer.msg(msg, { icon: 2, shade: 0.2, shadeClose: true, time: 4000 });
      }
    }
    return Promise.reject(error);
  }
);

window.addEventListener('unhandledrejection', function(event) {
  if (event.reason && event.reason.message) {
    console.warn('Unhandled Promise Rejection:', event.reason);
    if (window.layer && typeof window.layer.msg === 'function') {
      window.layer.msg('حدث خطأ غير متوقع. حاول مرة أخرى.', { icon: 2, time: 3000 });
    }
  }
});

window.addEventListener('online', function() {
  if (window.layer && typeof window.layer.msg === 'function') {
    window.layer.msg('🔄 تم استعادة الاتصال بالإنترنت!', { icon: 1, time: 2000 });
  }
});

window.addEventListener('offline', function() {
  if (window.layer && typeof window.layer.msg === 'function') {
    window.layer.msg('📡 لا يوجد اتصال بالإنترنت. يرجى التحقق من الاتصال.', { icon: 2, time: 4000, shade: 0.3, shadeClose: true });
  }
});

export default { showLoading, hideLoading, activeRequests };

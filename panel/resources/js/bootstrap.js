import axios from 'axios';
window.axios = axios;

// import $ from 'jquery';
// window.$ = window.jquery = $;


window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

axios.interceptors.request.use(
  config => {
    // Loading is managed centrally by http.js to avoid stacked layer.js
    // shades from multiple axios interceptors.
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);
axios.interceptors.response.use(
  response => {
    return response.data;
  },
  error => {
    return Promise.reject(error);
  }
);

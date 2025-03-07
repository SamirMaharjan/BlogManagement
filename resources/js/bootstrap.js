import axios from 'axios';


axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
window.axios = axios;

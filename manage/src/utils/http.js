import axios from 'axios'
import qs from 'qs'
// Vue.prototype.axios = axios
// Vue.use(qs);

axios.defaults.timeout = 15000; //响应时间
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=UTF-8'; //配置请求头
// axios.defaults.headers.post['Content-Type'] = 'multipart/form-data'; //配置请求头

if(localStorage.getItem('authorization')){
    // axios.defaults.headers.common['Authorization'] =  'Bearer ' + localStorage.getItem('authorization');
}

// axios.defaults.baseURL = process.env.NODE_ENV === 'production' ? process.env.VUE_APP_BASEURL : process.env.VUE_APP_BASEURL; //配置接口地址
let baseLink = 'http://comprehensive.cn/api';//http://bitcms.cn
//POST传参序列化(添加请求拦截器)
axios.interceptors.request.use((config) => {

    //在发送请求之前做某件事
    if (config.method === 'post') {
        config.data = qs.stringify(config.data);
    }

    return config;
}, (error) => {
    console.log('错误的传参')
    return Promise.reject(error);
});

//返回状态判断(添加响应拦截器)
axios.interceptors.response.use((res) => {
    // console.log(res);                                                                                                                                                                                                                                                                                                     
    if(res.data.code == -1){
        // localStorage.removeItem('authorization');
        // localStorage.removeItem('user');
        // window.location.href="/login";
    }

    //对响应数据做些事
    if (!res.data.success) {
        return Promise.resolve(res);
    }
    return res;
}, (error) => {
    
    console.log('网络异常')
    return Promise.reject(error);
});

//返回一个Promise(发送post请求)
export function fetchPost(url, params) {
    return new Promise((resolve, reject) => {
        axios.post(baseLink+url, params)
            .then(response => {
                resolve(response);
            }, err => {
                reject(err);
            })
            .catch((error) => {
                reject(error)
            })
    })
}
////返回一个Promise(发送get请求)
export function fetchGet(url, param) {
    // console.log(param);
    return new Promise((resolve, reject) => {
        // console.log(param);
        axios.get(baseLink+url, { params: param })
            .then(response => {
                resolve(response)
            }, err => {
                reject(err)
            })
            .catch((error) => {
                reject(error)
            })
    })
}
export default {
    fetchPost,
    fetchGet,
}
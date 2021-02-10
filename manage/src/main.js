import { createApp } from 'vue'
import App from './App.vue'
import router from './router/index'
import Antd from 'ant-design-vue/es';
import 'ant-design-vue/dist/antd.css';


//.use(Antd)
// createApp(App).use(router).mount('#app')
const app = createApp(App) // 创建实例
app.config.productionTip = false;

// app.prototype.axiosGet = fetchGet;
// app.prototype.axiosPost = fetchPost;


app.use(router)
// app.use(fetchPost)
// app.use(fetchPost)
app.use(Antd)

// 注册
app.directive('focus', {
    // Directive has a set of lifecycle hooks:
    // called before bound element's parent component is mounted
    beforeMount() {
        // console.log('----beforeMount----');
    },
    // called when bound element's parent component is mounted
    mounted(el,binding) {


        if (!permissionJudge(binding.value)) {
            el.parentNode.removeChild(el);
        }

        function permissionJudge(value) {
            // 此处sessionStorage.getItem('rule')代表vuex中储存的按钮菜单数据
            let list = localStorage.getItem('rule');
            console.log(list);
            if(list == null){
                return true;
            }
            
            let data= JSON.parse(localStorage.getItem('user'));

            if(data.role == 1){
                return true;
            }
            list = JSON.parse(list);
            for (let item of list) {
                if (item === value) {
                    return true;
                }
            }
            return false;
        }



        el.focus()
    },
    // called before the containing component's VNode is updated
    beforeUpdate() {
        // console.log('---beforeUpdate---');

    },
    // called after the containing component's VNode and the VNodes of its children // have updated
    updated() {
        // console.log('-----updated----');

    },
    // called before the bound element's parent component is unmounted
    beforeUnmount() {
        // console.log('-----beforeUnmount----');

    },
    // called when the bound element's parent component is unmounted
    unmounted() {
        // console.log('---unmounted---');

    }
  })







// app.use(http)
app.mount('#app')
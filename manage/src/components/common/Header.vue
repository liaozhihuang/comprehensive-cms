<template>

<a-layout id="components-layout-demo-custom-trigger">
    <a-layout-sider width="256" v-model:collapsed="collapsed" :trigger="null" collapsible>
        <div class="logo">
            <img src="../../assets/logo.png" />
        </div>

        <div >
            <a-menu v-model:openKeys="openKeys" v-model:selectedKeys="selectedKeys" mode="inline" theme="dark" :inline-collapsed="collapsed" @openChange="onOpenChange">
                <template v-for="(item) in menuList" >
                    <a-menu-item  v-if="!item.subs"  @click="menuClick(item)" :key="item.id"><AppstoreOutlined /><span>{{item.title}}</span></a-menu-item>
                    <a-sub-menu v-else  :key="item.id">
                        <template #title>
                            <span>
                                <AppstoreOutlined /><span>{{item.title}}</span>
                            </span>
                        </template>
                        <a-menu-item v-for="(vo) in item.subs" @click="menuClick(vo)" :key="vo.id">
                                {{vo.title}}
                        </a-menu-item>
                    </a-sub-menu>
                </template>
            </a-menu>
        </div>

    </a-layout-sider>
    <a-layout>

        <a-layout-header style="background: #fff; padding: 0">
            <a-button type="primary" @click="toggleCollapsed" style="margin-bottom: 16px">
                <MenuUnfoldOutlined v-if="collapsed" />
                <MenuFoldOutlined v-else />
            </a-button>
           
            <a-popover placement="bottom">
                <template #content>
                <p><a-button type="link" @click="loginOut()">退出</a-button></p>
                <p><a-button type="link" v-if="user.is_into_web"  @click="websiteOut()">退出站点</a-button> </p>
                </template>
                <template #title>
                    <span>操作</span>
                </template>
                <div class=" user-info-top">
                    <img src="../../assets/logo.png" />{{user.user_name}}
                </div>
            </a-popover>
        </a-layout-header>


        <a-layout-content :style="{ margin: '15px 16px', padding: '5px', minHeight: '280px' }">
           <router-view></router-view>
        </a-layout-content>
    </a-layout>
</a-layout>
</template>




<script>
import {fetchPost,fetchGet} from '../../utils/http'

import {
  MenuFoldOutlined,
  MenuUnfoldOutlined,
  PieChartOutlined,
  MailOutlined,
  DesktopOutlined,
  InboxOutlined,
  AppstoreOutlined,
} from '@ant-design/icons-vue';
export default {
    components: {
        MenuFoldOutlined,
        MenuUnfoldOutlined,
        PieChartOutlined,
        MailOutlined,
        DesktopOutlined,
        InboxOutlined,
        AppstoreOutlined,
    },
    data() {
        return {
            collapsed: true,  //折叠
            selectedKeys: [],
            openKeys: [],
            menuList:{},
            user:{},
        };
    },

    created() {
        let user= JSON.parse(localStorage.getItem('user'));
        let token= localStorage.getItem('authorization');
        if(token == undefined || token ==null){
            this.$router.push({
                path: '/login',
            });
            return false;
        }
        this.user = user;
        this.getMenu();
        let menu = JSON.parse(sessionStorage.getItem('menu'));
        this.selectedKeys = !menu ? [1] : [parseInt(menu.id)];
        this.openKeys = !menu ? [1] : [parseInt(menu.tid)];
	},


    watch: {
        openKeys(val, oldVal) {
            this.preOpenKeys = oldVal;
        },
    },

    methods: {

        //获取用户信息
        // getUserInfo(){
        //     let url = '/user/getUserInfo';
        //     fetchGet(url).then(res => {
        //         var ret = res.data;
        //         this.user = ret.data

        //         localStorage.setItem('user',JSON.stringify(ret.data));
        //     });
        // },

        //获取栏目
        getMenu(){
            let url = ''
            if(this.user.is_into_web){
                url = '/index/getWebsiteMenu';
            }else{
                url = '/index/getMenu';
            }
            fetchGet(url).then(res => {
                var ret = res.data;
                this.menuList = ret.data
            });
        },

        toggleCollapsed() {
            this.collapsed = !this.collapsed;
            this.openKeys = this.collapsed ? [] : this.preOpenKeys;
        },

        menuClick(row){
            this.selectedKeys = [row.id];
            sessionStorage.setItem('menu',JSON.stringify(row));

			this.$router.push({
				path: row.index,
            }); 
        },

        //退出
		loginOut(){
			let token = localStorage.getItem('authorization');
			if(token == null){
				this.$router.push({
					path: '/login',
				});
			}
			let url = '/auth/loginOut';
            let that = this
            fetchGet(url).then(res => {
				localStorage.removeItem('authorization');
				localStorage.removeItem('user');
				this.$router.push({
					path: '/login',
				});
            })
		},

        //退出站点
        websiteOut(){
            let url = '/auth/websiteOut';
            let that = this
            fetchGet(url).then(res => {
                //操作
                let ret = res.data;
                if(ret.code){
                    this.user.is_into_web = false;
                    localStorage.setItem('user',JSON.stringify(this.user));
                    // this.$router.push({
                    //     path: '/index',
                    // });
                    window.location.href="/index";
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })


        },

        onOpenChange(openKeys) {

        },
    },
};
</script>

<style>
#app {
    text-align: left;
    height: 100%;
}

.ant-layout.ant-layout-has-sider {
    min-height: 100%;
}

#components-layout-demo-custom-trigger .trigger {
    font-size: 18px;
    line-height: 64px;
    padding: 0 24px;
    cursor: pointer;
    transition: color 0.3s;
}

#components-layout-demo-custom-trigger .trigger:hover {
    color: #1890ff;
}

#components-layout-demo-custom-trigger .logo {
    height: 32px;
    background: rgba(255, 255, 255, 0.2);
    margin: 16px;
}

.user-info-top {
    float: right;
    margin: 0 20px 0 0;
    text-align: right;
}

.user-info-top img {
    width: 36px;
    height: 36px;
    float: left;
    margin: 13px 10px 0 0;
    border-radius: 100px;
}

.ant-menu.ant-menu-dark .ant-menu-item-selected,
.ant-menu-submenu-popup.ant-menu-dark .ant-menu-item-selected {
    background: #6fb737 !important;
}

#components-layout-demo-custom-trigger .logo {
    background: none !important;
}

#components-layout-demo-custom-trigger .logo img {
    height: 70%;
    margin: 0 10% auto;
}
</style>

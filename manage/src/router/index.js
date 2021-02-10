import { createRouter,createWebHistory } from 'vue-router'
import Index from "../components/pages/Index.vue"
import User from "../components/pages/User.vue"

import Header from '../components/common/Header.vue'


const router = createRouter({
	history: createWebHistory(), // hash模式：createWebHashHistory，history模式：createWebHistory
	routes: [
		{
			path: '/',
			redirect: '/index'
		},
		// {
		// 	path: '/login',
		// 	name: 'login',
		// 	component: Login,
		// },
		{
			path: '/header',
			name: 'header',
			component: Header,
			children: [
				{
					path: '/index',
					name: 'index',
					component:Index,
				},
				{
					path: '/user',
					name: 'user',
					component:User,
				},




			]
		},


	]
})

export default router
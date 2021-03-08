import { createRouter,createWebHistory } from 'vue-router'
import Index from "../components/pages/Index.vue"
import User from "../components/pages/User.vue"
import Role from "../components/pages/Role.vue"
import Node from "../components/pages/Node.vue"
import Menu from "../components/pages/Menu.vue"
import MenuGroup from "../components/pages/MenuGroup.vue"

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
				{
					path: '/role',
					name: 'role',
					component:Role,
				},
				{
					path: '/node',
					name: 'node',
					component:Node,
				},
				{
					path: '/menu',
					name: 'menu',
					component:Menu,
				},
				{
					path: '/menuGroup',
					name: 'menuGroup',
					component:MenuGroup,
				},




			]
		},


	]
})

export default router
<template>
    <div clas="app">
        <div class="crumbs">
            <AppstoreOutlined /> <span>管理员列表</span>
        </div>
        <div class="container">
            <a-alert
                message="Warning Text Warning Text Warning TextW arning Text Warning Text Warning TextWarning Text"
                type="warning"
                closable
                @close="onClose"
                class="remind"
            />

            <div class="handle-box">
                <a-button type="primary" v-focus="'/api/user/createUser'" @click="addView()" class="mr10"><template #icon><PlusOutlined /></template>创建</a-button>
                <a-input v-model:value="search" placeholder="管理员名称" class="handle-input mr10"></a-input>
                <a-button type="primary" @click="handleSearch">
                    <template #icon><SearchOutlined /></template>搜索
                </a-button>
            </div>
            <!-- 管理员列表 -->
			<a-table :columns="columns" :data-source="userAll" :indentSize="30" :pagination="pagination" :rowKey='record=>record.id' bordered>
                <!-- 状态 -->
                <template #status="{ status,record }">
                    <a-popconfirm placement="topRight" ok-text="是" cancel-text="否" v-if="record.id !=1"  @confirm="statusOperation(record)">
                        <template  #title>
                            <p v-if="record.status == 1">确定禁用此用户？</p>
                            <p v-else>确定启用此用户？</p>
                        </template>
                        <a-tag  :color="record.status == 1 ? 'green' :'red' " > {{record.status == 1 ?'正常' :"禁用" }}</a-tag>
                    </a-popconfirm>
                     <a-tag v-else :color="record.status == 1 ? 'green' :'red' " > {{record.status == 1 ?'正常' :"禁用" }}</a-tag>
                </template>

				<!-- 按钮 -->
				<template #operation="{record,index }" >
					<div class="editable-row-operations" v-if="record.id !=1">
						<a-button size="small" v-focus="'/api/user/updateUser'" type="link" @click="editView(record)" ><template #icon><EditOutlined /></template>编辑</a-button>
						<a-button size="small" v-focus="'/api/user/delUser'" type="link" @click="deluser(record,index)" ><template #icon><DeleteOutlined /></template>删除</a-button>
					</div>
				</template>
			</a-table>
        </div>


        <!--模态窗 -->
        <template>
            <div>
                <a-modal 
                    :title="ModalText" 
                    :visible="visible" 
                    :confirm-loading="confirmLoading" 
                    :centered="true"  
                    :maskClosable="false" 
                    @ok="handleSubmits"
                    width="55%"
                    @cancel="handleCancel">
                    <a-form :model="form" :label-col="labelCol" :wrapper-col="wrapperCol">

                        <a-form-item label="管理员名称" >
                            <a-input v-model:value="form.user_name" placeholder="管理员名称" />
                        </a-form-item>

                        <a-form-item label="管理员角色">
                            <a-select v-model:value="form.role" ref="select">
                                <a-select-option v-for="roles in roleAll" :value="roles.id"> {{roles.role_name}}</a-select-option>
                            </a-select>
                        </a-form-item>

                        <a-form-item label="登录密码" v-if="!form.id">
                           <a-input-password v-model:value="form.password" placeholder="请输入密码" />
                        </a-form-item>
                        <a-form-item label="确认密码" v-if="!form.id">
                            <a-input-password v-model:value="form.password2" placeholder="请确认密码" />
                        </a-form-item>

                        <a-form-item label="真实姓名">
                            <a-input v-model:value="form.real_name" placeholder="真实姓名" />
                        </a-form-item>
                        <a-form-item label="状态">
                            <a-radio-group v-model:value="form.status">
                                <a-radio :value="1">显示</a-radio>
                                <a-radio :value="0">隐藏</a-radio>
                            </a-radio-group>
                        </a-form-item>
                    </a-form>
                </a-modal>
            </div>
        </template>

    </div>
</template>


<script>
const columns = [
	{
		title: '用户名',
		dataIndex: 'user_name',
		slots: { customRender: 'user_name' },
		align: 'center'
	},
	{
		title: '角色',
		dataIndex: 'role_name',
		slots: { customRender: 'role_name' },
		align: 'center'
    },
	{
		title: '登陆次数',
		dataIndex: 'login_times',
		slots: { customRender: 'login_times' },
		align: 'center'
	},
	{
		title: '上次登陆ip',
		dataIndex: 'last_login_ip',
		slots: { customRender: 'last_login_ip' },
		align: 'center'
	},
	{
		title: '创建时间',
		dataIndex: 'add_time',
		slots: { customRender: 'add_time' },
		align: 'center'
	},
	{
		title: '状态',
		dataIndex: 'status',
		slots: { customRender: 'status' },
		align: 'center'
	},
	{
		title: '操作',
		key: 'operation',
		slots: { customRender: 'operation' },
		align: 'center'
	},
];

import {fetchPost,fetchGet} from '../../utils/http'

import {
    PlusOutlined,
    DeleteOutlined,
    SearchOutlined,
    EditOutlined,
    AppstoreOutlined
} from '@ant-design/icons-vue';

const forms = {
    status:1
} 

export default {
    components: {
        PlusOutlined,
        DeleteOutlined,
        EditOutlined,
        SearchOutlined,
        AppstoreOutlined,
    },
	data() {
		return {
            form:{},
            userAll:[], //列表
            roleAll:[],
            groupAll:[],
			columns,
			search:"", //搜索
			labelCol: { span: 4 },
			wrapperCol: { span: 18 },
			ModalText:'添加管理员', //模态窗标题
			visible: false, //模态窗状态
			confirmLoading: false,
            pagination: {
                pageNo: 1, //分页
                pageSize: 10, // 默认每页显示数量
                showSizeChanger: false, // 显示可改变每页数量
                pageSizeOptions: ["10", "20", "50", "100"], // 每页数量选项
                showTotal: total => `共${total}条`, // 显示总数
                onShowSizeChange: (pageSize) =>(this.pageSize = pageSize), // 改变每页数量时更新显示
                onChange:this.onPageChange.bind(this), //点击页码事件
                total: 0, //总条数
                current: 0, //页码
                buildOptionText:pageSizeOptions => `${pageSizeOptions.value}条/页`,
            },
		};
    },

    mounted() {
        this.form = JSON.parse(JSON.stringify(forms));
        this.getData();
        this.getRoleAll();
    },
   

	methods: {
		getData() {
            let url = '/admin/user';
            let that = this
            let pages = this.pagination;
			let params = {};
				params.pageIndex = pages.pageNo;
				params.pageSize = pages.pageSize;
				params.search = this.search;
            fetchGet(url,params).then(res => {
                var ret = res.data;
				this.userAll = ret.data;
				this.pagination.total = ret.count;
            })
        },

        //获取角色
        getRoleAll(){
            let url = '/admin/role/getRole';
            fetchGet(url).then(res => {
                var ret = res.data;
                this.roleAll = ret.data;
            })
        },

        //搜索
		handleSearch(e){
            this.pagination.pageNo = 1;
            this.pagination.current = 1;
			this.getData();
		},

		//分页
		onPageChange(page){
			this.pagination.current = page;
			this.pagination.pageNo = page;
			this.getData();
        },

        //添加窗口
        addView(){
            this.ModalText="添加管理员";
            this.visible = true;
        },

        //编辑窗口
        editView(row){
            this.visible = true;
            this.form.id = row.id;
            this.form.user_name = row.user_name;
            this.form.role = row.role;
            this.form.group_id = row.group_id;
            this.form.real_name = row.real_name;
            this.form.status = row.status;
            this.ModalText="编辑管理员";
        },

        //提交数据
        handleSubmits(){
            let url = '';
            if(this.form.id != undefined){
				url = '/admin/user/updateUser';
			}else{
				url = '/admin/user/createUser';
			}
            fetchPost(url,this.form).then(res => {
                var ret = res.data;
                if(ret.code == 1){
					this.handleCancel();
                    this.$message.success(ret.msg);
                    this.getData();
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        },

        //关闭窗口
        handleCancel(){
            this.visible = false;
    
            this.form = {};
            this.form = JSON.parse(JSON.stringify(forms));
        },

        //用户操作
        statusOperation(row){
            let url = '/admin/user/statusOperation';
 			let params = {};
            params.id = row.id;
            fetchGet(url,params).then(res => {
               	var ret = res.data;
                if(ret.code == 1){
                    row.status = row.status == 1? 0 : 1;
                    this.$message.success(ret.msg);
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        },

        //删除
        deluser(row,index){
            let url = '/admin/user/delUser';
 			let params = {};
            params.id = row.id;
            fetchGet(url,params).then(res => {
               	var ret = res.data;
                if(ret.code == 1){
                   this.userAll.splice(index, 1);
                    this.$message.success(ret.msg);
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        },

		aSelect(e){
			this.$forceUpdate();
		}
	},
};
</script>
<style scoped>
.handle-box {
    margin-bottom: 20px;
}
.container{
    padding: 30px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
}
.handle-input {
    width: 300px;
    display: inline-block;
}
.mr10 {
    margin-right: 10px;
}
#components-a-popconfirm-demo-placement .ant-btn {
  width: 70px;
  text-align: center;
  padding: 0;
  margin-right: 8px;
  margin-bottom: 8px;
}
.mr10 {
    margin-right: 10px;
}
</style>
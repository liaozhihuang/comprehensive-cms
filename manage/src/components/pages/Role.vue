<template>
    <div clas="app">
        <div class="crumbs">
            <AppstoreOutlined /> 角色列表
        </div>
        <div class="container">
            <div class="handle-box">
                <a-button type="primary" @click="addView()" v-focus="'/api/role/createRole'" class="mr10"><template #icon><PlusOutlined /></template>创建</a-button>
                <a-input v-model:value="search" placeholder="角色名称" class="handle-input mr10"></a-input>
                  <a-button type="primary" @click="handleSearch">
                    <template #icon><SearchOutlined /></template>搜索
                </a-button>
            </div>
            <!-- 角色列表 -->
			<a-table :columns="columns" :data-source="roleAll" :indentSize="30" :pagination="pagination" :rowKey='record=>record.id'>
				<!-- 按钮 -->
				<template #operation="{record }" >
					<div class="editable-row-operations" v-if="record.id !=1">
						<a-button size="small" type="link" v-focus="'/api/role/updateRole'" @click="editView(record)" ><template #icon><EditOutlined /></template>编辑</a-button>
						<a-button size="small" v-focus="'/api/role/giveAccess'" type="link" @click="getNodeTree(record)" ><template #icon><ApartmentOutlined /></template>分配权限</a-button>
						<a-button size="small" v-focus="'/api/role/delRole'" type="link" @click="delRole(record,index)" ><template #icon><DeleteOutlined /></template>删除</a-button>
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
                    width="50%"
                    @cancel="handleCancel">
                    <a-form :model="form" :label-col="labelCol" :wrapper-col="wrapperCol">
                        <a-form-item label="角色名称" >
                            <a-input v-model:value="form.role_name" placeholder="角色名称" />
                        </a-form-item>
                    </a-form>
                </a-modal>
            </div>
        </template>

        <template>
            <div>
                <a-modal 
                    title="分配权限节点" 
                    :visible="tree" 
                    :confirm-loading="confirmLoading" 
                    :centered="true"  
                    :maskClosable="false" 
                    @ok="treeSubmits"
                    width="50%"
                    @cancel="treeCancel">

                    <a-tree
                        v-model:checkedKeys="checked_node"
                        :checkable="true"
                        :auto-expand-parent="autoExpandParent"
                        :tree-data="treeData"
                        @check="treeClick"
                    />

                </a-modal>
            </div>
        </template>




    </div>
</template>


<script>
const columns = [
	{
		title: '角色',
		dataIndex: 'role_name',
		slots: { customRender: 'role_name' },
		align: 'center'
	},
	{
		title: '创建时间',
		dataIndex: 'add_time',
		slots: { customRender: 'add_time' },
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
    ApartmentOutlined,
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
        ApartmentOutlined,
        PlusOutlined,
        DeleteOutlined,
        EditOutlined,
        SearchOutlined,
        AppstoreOutlined,
    },
	data() {
		return {
            form:{},
            roleAll:[], //列表
			columns,
			search:"", //搜索
			labelCol: { span: 4 },
			wrapperCol: { span: 18 },
			ModalText:'', //模态窗标题
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
            tree:false,
            treeData:{},
            checked_node:[],
            checked_id:'',
            autoExpandParent: true,
		};
    },

    mounted() {
        this.form = forms;
        this.getData();
    },
   

	methods: {
		getData() {
            let url = '/role';
            let that = this
            let pages = this.pagination;
			let params = {};
				params.pageIndex = pages.pageNo;
				params.pageSize = pages.pageSize;
				params.search = this.search;
            fetchGet(url,params).then(res => {
                var ret = res.data;
				this.roleAll = ret.data;
				this.pagination.total = ret.count;
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
            this.ModalText="创建角色";
            this.visible = true;

        },

        //编辑窗口
        editView(row){
            this.visible = true;
            this.form.id = row.id;
            this.form.role_name = row.role_name;
            this.ModalText="编辑角色";
        },
        //分配权限
        getNodeTree(row){
            let url = '/node/getNodeTree';
            let params = {
                id:row.id
            }
            fetchGet(url,params).then(res => {
                var ret = res.data;
                if(ret.code == 1){
                    this.tree = true;
                    this.treeData = ret.data.nodeAll;
                    this.checked_node = ret.data.checked;
                    this.checked_id = row.id;
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        },

        //提交节点树
        treeSubmits(){
            let params = {}
                // params.node = JSON.stringify(this.checked_node);
                params.node = this.checked_node.toString();
                params.id = this.checked_id;
            let url = '/role/giveAccess';
            
            fetchPost(url,params).then(res => {
                var ret = res.data;
                if(ret.code == 1){
					this.treeCancel();
                    this.$message.success(ret.msg);
                    this.getData();
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        },

        //关闭树
        treeCancel(){
            this.tree = false;
            this.checked_node = [];
        },

        //提交数据
        handleSubmits(){
            let url = '';
            if(this.form.id != undefined){
				url = '/role/updateRole';
			}else{
				url = '/role/createRole';
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

        //删除
        delRole(row,index){
            let url = '/role/delRole';
 			let params = {};
            params.id = row.id;
            fetchGet(url,params).then(res => {
               	var ret = res.data;
                if(ret.code == 1){
                   this.roleAll.splice(index, 1);
                    this.$message.success(ret.msg);
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        },

        treeClick(checkedKeys, info){
        },


		aSelect(e){
			this.$forceUpdate();
		}
	},
};
</script>

<template>
    <div clas="app">
        <div class="crumbs">
            <AppstoreOutlined /> 菜单组
        </div>
        <div class="container">
            <div class="handle-box">
                <a-button type="primary" @click="addView()" class="mr10"><template #icon><PlusOutlined /></template>创建</a-button>
                <a-input v-model:value="search" placeholder="菜单组名称" class="handle-input mr10"></a-input>
                  <a-button type="primary" @click="handleSearch">
                    <template #icon><SearchOutlined /></template>搜索
                </a-button>
            </div>
            <!-- 菜单组列表 -->
			<a-table :columns="columns" :data-source="groupAll" :indentSize="30" :pagination="pagination" :rowKey='record=>record.id'>
				<!-- 按钮 -->
				<template #operation="{record,index }" >
					<div class="editable-row-operations">
						<a-button size="small" type="link" @click="editView(record)" ><template #icon><EditOutlined /></template>编辑</a-button>
                        <a-popconfirm placement="topRight" ok-text="是" cancel-text="否" @confirm="delGroup(record,index)">
							<template #title>
								<p>确定删除?</p>
							</template>
							<a-button size="small" type="link"> <template #icon><DeleteOutlined /></template>删除</a-button>
						</a-popconfirm>
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
                        <a-form-item label="菜单组名称" >
                            <a-input v-model:value="form.group_name" @input="change($event)" placeholder="菜单组名称" />
                        </a-form-item>

                        <a-form-item label="菜单组标识" >
                            <a-input v-model:value="form.group_sign" @input="change($event)" :disabled="disabled" placeholder="菜单组标识" />
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
		title: '名称',
		dataIndex: 'group_name',
		slots: { customRender: 'group_name' },
		align: 'center'
	},
    {
		title: '标识',
		dataIndex: 'group_sign',
		slots: { customRender: 'group_sign' },
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
            groupAll:[], //列表
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
            disabled:false,
		};
    },

    mounted() {
        this.getData();
    },
   

	methods: {
		getData() {
            let url = '/admin/menuGroup';
            let that = this
            let pages = this.pagination;
			let params = {};
				params.pageIndex = pages.pageNo;
				params.pageSize = pages.pageSize;
				params.search = this.search;
            fetchGet(url,params).then(res => {
                var ret = res.data;
				this.groupAll = ret.data;
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
            this.ModalText="创建菜单组";
            this.visible = true;
        },

        //编辑窗口
        editView(row){
            this.visible = true;
            this.form.id = row.id;
            this.form.group_name = row.group_name;
            this.form.group_sign = row.group_sign;
            this.ModalText="编辑菜单组";
            this.disabled = true;
        },

        //提交数据
        handleSubmits(){
            let url = '';
            if(this.form.id != undefined){
				url = '/admin/menuGroup/updateMenuGroup';
			}else{
				url = '/admin/menuGroup/createMenuGroup';
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
            this.disabled = false;
            this.visible = false;
            this.form = {}

            
        },

        //删除
        delGroup(row,index){
            let url = '/admin/menuGroup/delMenuGroup';
 			let params = {};
            params.id = row.id;
            fetchGet(url,params).then(res => {
               	var ret = res.data;
                if(ret.code == 1){
                //    this.groupAll.splice(index, 1);
                    this.getData();
                    this.$message.success(ret.msg);
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        },

        change(){
            this.$forceUpdate();
        },
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
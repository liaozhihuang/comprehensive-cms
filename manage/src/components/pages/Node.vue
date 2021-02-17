<template>
    <div clas="app">
        <div class="crumbs">
            <AppstoreOutlined /> 节点树
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
                <a-button type="primary" v-focus="'/api/node/createNode'"  @click="addView()" class="mr10"><template #icon><PlusOutlined /></template>添加顶级节点</a-button>
                <a-button type="primary"  @click="refresh"><template #icon><ReloadOutlined /></template>刷新节点</a-button>
            </div>

            <!-- 节点树 -->
            <a-tree
                :checkable="false"
                :auto-expand-parent="autoExpandParent"
                :tree-data="nodeAll"
                @select="onSelect"
            />
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
                        <a-form-item label="节点名称" >
                            <a-input v-model:value="form.node_name" placeholder="节点名称" />
                        </a-form-item>

                        <a-form-item label="所属节点" v-if="!form.id">
                            <a-input v-model:value="ascription" disabled placeholder="节点名称" />
                        </a-form-item>

                        <a-form-item label="是否菜单项">
                            <a-select v-model:value="form.is_menu" ref="select">
                                <a-select-option v-for="item in options" :value="item.value"> {{item.label}}</a-select-option>
                            </a-select>
                        </a-form-item>

                        <a-form-item label="请求地址(后台)" >
                            <a-input v-model:value="form.node_path" placeholder="节点地址（后台）" />
                        </a-form-item>

                        <a-form-item label="请求地址(前台)" v-if="form.is_menu == 2">
                            <a-input v-model:value="form.reception_path" placeholder="请求地址(前台)" />
                        </a-form-item>

                        <a-form-item label="icon样式" v-if="form.is_menu == 2">
                            <a-input v-model:value="form.style" placeholder="icon样式" />
                        </a-form-item>

                    </a-form>
                </a-modal>
            </div>
        </template>


        <template>
            <div>
                <a-modal 
                    :title="treeTitle" 
                    :visible="tree_view"
                    :centered="true"  
                    :maskClosable="false" 
                    width="28%"
                    :footer="null"
                    style="text-align:center"
                    @cancel="treeCancel">
                    <a-button type="primary" v-focus="'/api/node/createNode'"  @click="addSon()" class="mr10"><template #icon><PlusOutlined /></template>添加子节点</a-button>
                    <a-button type="primary" v-focus="'/api/node/updateNode'"  @click="editView()" class="mr10"><template #icon><EditOutlined /></template>编辑节点</a-button>
                    <a-button type="danger" v-focus="'/api/node/delNode'"  @click="delNode()"><template #icon><DeleteOutlined /></template>删除节点</a-button>
                </a-modal>
            </div>
        </template>



    </div>
</template>


<script>


import {fetchPost,fetchGet} from '../../utils/http'

import {
    ReloadOutlined,
    ApartmentOutlined,
    PlusOutlined,
    DeleteOutlined,
    EditOutlined,
    AppstoreOutlined
} from '@ant-design/icons-vue';

const forms = {
    type_id:0, //0表示顶级节点
    is_menu:2,
} 

export default {
    components: {
        ReloadOutlined,
        ApartmentOutlined,
        PlusOutlined,
        DeleteOutlined,
        EditOutlined,
        AppstoreOutlined,
    },
	data() {
		return {
            form:{},
            nodeAll:[], //列表
			search:"", //搜索
			labelCol: { span: 4 },
			wrapperCol: { span: 18 },
			ModalText:'', //模态窗标题
			visible: false, //模态窗状态
			confirmLoading: false,

            ascription:"顶级节点",
            options:[
                { value:1,label:"否" },
                { value:2,label:"是" }
            ],

            nodeData:{},
            autoExpandParent: true,
            tree_view:false,
            treeTitle:"您要如何操作该节点"

		};
    },

    mounted() {
        this.form = JSON.parse(JSON.stringify(forms));
        this.getData();
    },

	methods: {
        onSelect(selectedKeys, info) {

            let tree= info.selectedNodes[0].props;

            this.nodeData.id = tree.id;
            this.nodeData.node_name = tree.title;
            this.nodeData.is_menu = tree.is_menu;
            this.nodeData.node_path = tree.node_path;
            this.nodeData.reception_path = tree.reception_path;
            this.nodeData.style = tree.style;
            this.nodeData.type_id = tree.pid;

            this.tree_view = true;

        },
        //关闭操作窗口
        treeCancel(){
            // this.nodeData = {};
            this.tree_view = false;
        },


		getData() {
            let url = '/admin/node';
            fetchGet(url).then(res => {
                var ret = res.data;
				this.nodeAll = ret.data;
            })
        },

        //搜索
		handleSearch(e){
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
            this.ModalText="添加顶级节点";
            this.visible = true;
        },

        //编辑窗口
        editView(row){
            this.visible = true;
            this.tree_view = false;
            this.ModalText="编辑节点";
            this.form = this.nodeData;
        },

        //添加子节点窗口
        addSon(){
            this.ModalText="添加子结点节点";
            this.ascription = this.nodeData.node_name;
            this.tree_view = false;
            this.visible = true;
            this.form.type_id = this.nodeData.id
        },

        //刷新
        refresh(){
            this.getData();
            this.$message.success('刷新成功');
        },

        //提交数据
        handleSubmits(){
            let url = '';
            if(this.form.id != undefined){
				url = '/admin/node/updateNode';
			}else{
				url = '/admin/node/createNode';
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
            this.ascription = "顶级节点";
           
            this.form = {};
            this.form = JSON.parse(JSON.stringify(forms));
        },

        //删除
        delNode(row,index){
            let url = '/admin/node/delNode';
 			let params = {};
            params.id = this.nodeData.id;
            fetchGet(url,params).then(res => {
               	var ret = res.data;
                if(ret.code == 1){
                    this.treeCancel();
                    this.getData()
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